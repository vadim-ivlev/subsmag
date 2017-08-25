<?php

namespace Rg\ApiBundle\Service;


use Rg\ApiBundle\Entity\Order;

class Platron
{
//    const MERCHANT_ID = 3716;
//    const SECRET_KEY = 'wowakedalapadasy';
    private $salt;
    const MERCHANT_ID = 8888;
    const SECRET_KEY = 'xywoxelepejepowi';

    const ORDER_STATUS = [
        'partial' => 'Транзакция создается',
        'pending' => 'Ожидание оплаты',
        'ok' => 'Оплачен',
        'failed' => 'Платеж не прошел',
        'revoked' => 'Платеж отозван'
    ];
    const HOST_TO_HOST = 'https://www.platron.ru/init_payment.php';

//    const BASE_URL = 'https://subsmag.rg.ru';
    const BASE_URL = 'http://subsmag.loc';

    public function init(Order $order)
    {

        $request = $this->prepareRequest($order);

        $response_xml_str = $this->sendRequest($request);

        $response_simple_xml = new \SimpleXMLElement($response_xml_str);

        if (!$this->simpleValidate($response_simple_xml)) {
            throw new \Exception('Invalid response from Platron');
        }

        return $response_simple_xml;
    }

    private function prepareRequest(Order $order)
    {
        $params = new \stdClass();
        $params->pg_merchant_id = 8888;
        $params->pg_order_id = $order->getId();
        $params->pg_amount = $order->getTotal();
        $params->pg_currency = 'RUB';

        $params->pg_check_url = self::BASE_URL . '/platron/check/';
        $params->pg_result_url = self::BASE_URL . '/platron/result/';

        $params->pg_request_method = 'GET';

        $params->pg_success_url = self::BASE_URL . '/platron/success/';
        $params->pg_failure_url = self::BASE_URL . '/platron/failure/';

        $params->pg_description = "Оплата подписки. Заказ №" . $order->getId();

        $params->pg_testing_mode = 1;

        $this->salt = md5(time());
        $params->pg_salt = $this->salt;

        $params = (array) $params;
        ksort($params, SORT_STRING);

        $joined = '';
        foreach ($params as $value) {
            $joined .= $value . ';';
        }
        $pg_sig = md5('init_payment.php;' . $joined . self::SECRET_KEY);

        $params['pg_sig'] = $pg_sig;

        return $params;
    }

    private function sendRequest(array $params)
    {
        $ch = curl_init();
        $query = http_build_query($params);
        $params = [
            CURLOPT_URL => self::HOST_TO_HOST,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $query,
        ];

        curl_setopt_array($ch, $params);
        return curl_exec($ch);
    }

    private function simpleValidate(\SimpleXMLElement $xml)
    {
        $condition = (string) $xml->pg_status == 'ok'
            && ( (string) $xml->pg_salt == $this->salt)
            && filter_var((string) $xml->pg_redirect_url, FILTER_VALIDATE_URL) !== FALSE
            && filter_var((string) $xml->pg_payment_id, FILTER_VALIDATE_INT) !== FALSE
        ;

        return $condition;
    }
}