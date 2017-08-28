<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlatronController extends Controller
{
    public function checkURLAction(Request $request)
    {
        /*
         * Проверка возможности платежа.
         * Приходит от Платрона.
         * //TODO: Закрыть для всех, кроме Платрона
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
            $xml = $this->get('rg_api.platron')->prepareRejectCheck($pg_xml,'Не найдён заказ с номером ' . $order_id . '.');
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
            $xml = $this->get('rg_api.platron')->prepareRejectCheck($pg_xml,'Не совпадают данные заказа и оплаты.');
        }

        return new Response($xml->asXML());

    }

    public function resultURLAction(Request $request)
    {

    }

    public function successURLAction(Request $request)
    {

    }

    public function failureURLAction(Request $request)
    {

    }
}
