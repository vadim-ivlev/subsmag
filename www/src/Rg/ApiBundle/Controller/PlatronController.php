<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * //TODO: Закрыть для всех, кроме Платрона
 * Class PlatronController
 * @package Rg\ApiBundle\Controller
 */
class PlatronController extends Controller
{
    public function checkURLAction(Request $request)
    {
        /*
         * Проверка возможности платежа.
         * Приходит от Платрона.
         * Полный список: https://front.platron.ru/docs/api/checking_possibility_payment/
        pg_payment_system
        pg_result
        pg_payment_date
        pg_can_reject

        pg_user_phone
        pg_need_phone_notification

        pg_user_contact_email
        pg_need_email_notification

        pg_failure_code
        pg_failure_description

        pg_salt
        pg_sig
        */

        $pg_xml_str = $request->request->get('pg_xml');
        $pg_xml = new \SimpleXMLElement($pg_xml_str);

        # допустим, проверена подпись и есть все поля.
        # проверить совпадение номера и стоимости заказа, pg_payment_id
        $order_id = (int) $pg_xml->pg_order_id;
        $payment_id = (string) $pg_xml->pg_payment_id;
        $amount = (float) $pg_xml->pg_amount;
        $currency = (int) $pg_xml->pg_currency;

        $order = $this->getDoctrine()->getRepository('RgApiBundle:Order')
            ->findOneBy(['id' => $order_id]);
        if (!$order) {
            $message = 'Не найдён заказ с номером ' . $order_id . '.';
            $xml = $this->get('rg_api.platron')->prepareErrorOnCheck($pg_xml, $message);
            return new Response($xml->asXML());
        }

        $condition = $order->getTotal() == $amount
            && $order->getPgPaymentId() == $payment_id
        ;
        if ($condition) {
            // send ok
            $xml = $this->get('rg_api.platron')->prepareAgreeWithCheck($pg_xml);
        } else {
            // send error
            $message = 'Не совпадают данные заказа и оплаты.';
            $xml = $this->get('rg_api.platron')->prepareErrorOnCheck($pg_xml, $message);
        }

        return new Response($xml->asXML());

    }

    public function resultURLAction(Request $request)
    {
        $pg_xml_str = $request->request->get('pg_xml');
        $pg_xml = new \SimpleXMLElement($pg_xml_str);

        # валидация
        $pg_result = (string) $pg_xml->pg_result;
        if (!in_array($pg_result, ['0', '1'], true)) {
            $message = 'Не распознан статус оплаты';
            $xml = $this->get('rg_api.platron')->prepareErrorOnResult($pg_xml, $message);

            return new Response($xml->asXML());
        }
        # допустим, проверена подпись и есть все поля.

        # проверить совпадение номера и стоимости заказа, pg_payment_id
        $order_id = (int) $pg_xml->pg_order_id;
        $payment_id = (string) $pg_xml->pg_payment_id;
        $amount = (float) $pg_xml->pg_amount;
        $currency = (int) $pg_xml->pg_currency;

        $doctrine = $this->getDoctrine();
        $order = $doctrine->getRepository('RgApiBundle:Order')
            ->findOneBy(['id' => $order_id]);
        if (!$order) {
            $message = 'Не найдён заказ с номером ' . $order_id . '.';
            $xml = $this->get('rg_api.platron')->prepareErrorOnResult($pg_xml, $message);

            return new Response($xml->asXML());
        }

        $condition = $order->getTotal() == $amount
            && $order->getPgPaymentId() == $payment_id
        ;
        if (!$condition) {
            // answer error to Platron
            $message = 'Не совпадают данные заказа и оплаты.';
            $xml = $this->get('rg_api.platron')->prepareErrorOnResult($pg_xml, $message);

            return new Response($xml->asXML());
        }

        if ($pg_result === '0') {
            # Неудача платежа.
            $message = 'Код причины отказа: ' . (string) $pg_xml->pg_failure_code;
            $message .= '. Причина неудачи платежа: ' . (string) $pg_xml->pg_failure_description;

            $xml = $this->get('rg_api.platron')->prepareRejectedOnResult($pg_xml, $message);

            return new Response($xml->asXML());
        }

        ## Успешный платёж.
        # изменить статус заказа на "оплачено"
        $order->setIsPaid(true);

        $em = $doctrine->getManager();
        $em->persist($order);
        $em->flush();

        // send ok to Platron
        $xml = $this->get('rg_api.platron')->prepareOkOnResult($pg_xml);

        return new Response($xml->asXML());

    }

    /**
     * Браузер пользователя редиректится Платроном на этот адрес только при успешной оплате.
     * https://front.platron.ru/docs/api/return_site_merchant/
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function successURLAction(Request $request)
    {
        /*
        * Образец редиректа в случае успешной оплаты
            https://store.ru/success.php?
            pg_salt=54c6a8786f19e
            pg_order_id=654
            pg_payment_id=765432
            uservar1=45363456
            pg_card_brand=CA
            pg_card_pan=527594******4984
            pg_card_hash=022380c107141f7e11f4271d7f6412a715222c32
            pg_auth_code=014318
            pg_auth_code=014318
            pg_sig=20bcedd8320ac8868b97706abedec0b4
        */
        $pg_order_id = $request->query->get('pg_order_id');

        $resp = [
            'description' => 'Заказ ' . $pg_order_id . ' успешно оплачен через Platron и передан в отдел подписки.',
        ];

        return (new Outer())->json($resp);
    }

    /**
     * Браузер пользователя редиректится Платроном на этот адрес при ошибке.
     *
     * Ситуации редиректа:
     * https://front.platron.ru/docs/api/payment_result/
     * https://front.platron.ru/docs/api/checking_possibility_payment/
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function failureURLAction(Request $request)
    {
        $query = $request->query;

        # Неудача платежа.
        $message = 'Код причины отказа: ' . $query->get('pg_failure_code');
        $message .= '. Причина неудачи платежа: ' . $query->get('pg_failure_description');

        $pg_order_id = $query->get('pg_order_id');

        $resp = [
            'description' => 'Произошла ошибка при оплате заказа ' . $pg_order_id . '. ' . $message,
        ];

        return (new Outer())->json($resp);
    }
}
