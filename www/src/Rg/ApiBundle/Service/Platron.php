<?php

namespace Rg\ApiBundle\Service;


use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Exception\PlatronException;

class Platron
{
//    const MERCHANT_ID = 3716;
//    const SECRET_KEY = 'wowakedalapadasy';
    private $salt;
    const MERCHANT_ID = 8888;
    const SECRET_KEY = 'xywoxelepejepowi';

    const HOST_TO_HOST = 'https://www.platron.ru/init_payment.php';

//    const API_URL = 'https://subsmag.rg.ru/api';
//    const BASE_URL = 'https://subsmag.rg.ru';
    const API_URL = 'https://rg.ru/subsmag/api';
    const BASE_URL = 'https://rg.ru/subsmag';

    public function init(Order $order)
    {

        $request = $this->prepareRequest($order);

        $response_xml_str = $this->sendRequest($request);

        try {
            $response_simple_xml = new \SimpleXMLElement($response_xml_str);
        } catch (\Exception $e) {
            throw new PlatronException('Unparseable response from Platron.');
        }

        if (!$this->simpleValidateInit($response_simple_xml)) {
            throw new \Exception('Invalid response from Platron');
        }

        return $response_simple_xml;
    }

    /**
     * Подтверждение готовности заказа и правильности суммы
     * @param \SimpleXMLElement $pg_xml
     * @return \SimpleXMLElement
     */
    public function prepareAgreeWithCheck(\SimpleXMLElement $pg_xml)
    {
        $xml_str = <<<RESPONSE_OK
<?xml version="1.0" encoding="utf-8"?>
  <response>
	<pg_salt></pg_salt>
	<pg_status>ok</pg_status>
	<pg_timeout>600</pg_timeout>
	<pg_sig></pg_sig>
  </response>
RESPONSE_OK;

        $params = [
            'pg_salt' => (string) $pg_xml->pg_salt,
            'pg_status' => 'ok',
            'pg_timeout' => '600',
        ];

        # count signature
        ksort($params, SORT_STRING);

        $joined = '';
        foreach ($params as $value) {
            $joined .= $value . ';';
        }
        $pg_sig = md5('check;' . $joined . self::SECRET_KEY);
        # end count

        $simple_xml = new \SimpleXMLElement($xml_str);
        $simple_xml->pg_salt = $params['pg_salt'];
        $simple_xml->pg_sig = $pg_sig;

        return $simple_xml;
    }

    public function prepareOkOnResult(\SimpleXMLElement $pg_xml)
    {
        $url_tail = 'result';

        // поле pg_description в случае успеха не передаётся
        $xml_str = <<<RESPONSE_OK
<?xml version="1.0" encoding="utf-8"?>
  <response>
	<pg_salt></pg_salt>
	<pg_status>ok</pg_status>
	<pg_sig></pg_sig>
  </response>
RESPONSE_OK;


        $params = [
            'pg_salt' => (string) $pg_xml->pg_salt,
            'pg_status' => 'ok',
        ];

        # count signature
        ksort($params, SORT_STRING);

        $joined = '';
        foreach ($params as $value) {
            $joined .= $value . ';';
        }
        $pg_sig = md5($url_tail . ';' . $joined . self::SECRET_KEY);
        # end count

        $simple_xml = new \SimpleXMLElement($xml_str);
        $simple_xml->pg_salt = (string) $pg_xml->pg_salt;
        $simple_xml->pg_sig = $pg_sig;

        return $simple_xml;
    }

    public function prepareRejectedOnResult(\SimpleXMLElement $pg_xml, string $message)
    {
        $url_tail = 'result';

        $xml_str = <<<RESPONSE_REJECT
<?xml version="1.0" encoding="utf-8"?>
  <response>
	<pg_salt></pg_salt>
	<pg_status>rejected</pg_status>
	<pg_description></pg_description>
	<pg_sig></pg_sig>
  </response>
RESPONSE_REJECT;

        $params = [
            'pg_salt' => (string) $pg_xml->pg_salt,
            'pg_status' => 'rejected',
            'pg_description' => $message,
        ];

        # count signature
        ksort($params, SORT_STRING);

        $joined = '';
        foreach ($params as $value) {
            $joined .= $value . ';';
        }
        $pg_sig = md5($url_tail . ';' . $joined . self::SECRET_KEY);
        # end count

        $simple_xml = new \SimpleXMLElement($xml_str);
        $simple_xml->pg_salt = $params['pg_salt'];
        $simple_xml->pg_sig = $pg_sig;
        $simple_xml->pg_description = $message;

        return $simple_xml;
    }

    public function prepareErrorOnResult(\SimpleXMLElement $pg_xml, string $message)
    {
        $url = 'result';
        $xml_str = <<<RESPONSE_ERROR
<?xml version="1.0" encoding="utf-8"?>
  <response>
	<pg_salt></pg_salt>
	<pg_status>error</pg_status>
	<pg_error_code>1</pg_error_code>
	<pg_error_description></pg_error_description>
	<pg_description></pg_description>
	<pg_sig></pg_sig>
  </response>
RESPONSE_ERROR;

        $params = [
            'pg_salt' => (string) $pg_xml->pg_salt,
            'pg_status' => 'error',
            'pg_error_code' => 1,
            'pg_error_description' => $message,
            'pg_description' => $message,
        ];

        # count signature
        ksort($params, SORT_STRING);

        $joined = '';
        foreach ($params as $value) {
            $joined .= $value . ';';
        }
        $pg_sig = md5($url . ';' . $joined . self::SECRET_KEY);
        # end count

        $simple_xml = new \SimpleXMLElement($xml_str);
        $simple_xml->pg_salt = $params['pg_salt'];
        $simple_xml->pg_sig = $pg_sig;
        $simple_xml->pg_error_description = $message;
        $simple_xml->pg_description = $message;

        return $simple_xml;
    }

    public function prepareErrorOnCheck(\SimpleXMLElement $pg_xml, string $message)
    {
        $xml_str = <<<RESPONSE_ERROR
<?xml version="1.0" encoding="utf-8"?>
  <response>
	<pg_salt></pg_salt>
	<pg_status>error</pg_status>
	<pg_error_code>1</pg_error_code>
	<pg_error_description></pg_error_description>
	<pg_sig></pg_sig>
  </response>
RESPONSE_ERROR;

        $params = [
            'pg_salt' => (string) $pg_xml->pg_salt,
            'pg_status' => 'error',
            'pg_error_code' => 1,
            'pg_error_description' => $message,
        ];

        # count signature
        ksort($params, SORT_STRING);

        $joined = '';
        foreach ($params as $value) {
            $joined .= $value . ';';
        }
        $pg_sig = md5('check;' . $joined . self::SECRET_KEY);
        # end count

        $simple_xml = new \SimpleXMLElement($xml_str);
        $simple_xml->pg_salt = $params['pg_salt'];
        $simple_xml->pg_sig = $pg_sig;
        $simple_xml->pg_error_description = $message;

        return $simple_xml;
    }

    public function prepareRejectCheck(\SimpleXMLElement $pg_xml, string $message)
    {
        $xml_str = <<<RESPONSE_REJECT
<?xml version="1.0" encoding="utf-8"?>
  <response>
	<pg_salt></pg_salt>
	<pg_status>rejected</pg_status>
	<pg_description></pg_description>
	<pg_sig></pg_sig>
  </response>
RESPONSE_REJECT;

        $params = [
            'pg_salt' => (string) $pg_xml->pg_salt,
            'pg_status' => 'rejected',
            'pg_description' => $message,
        ];

        # count signature
        ksort($params, SORT_STRING);

        $joined = '';
        foreach ($params as $value) {
            $joined .= $value . ';';
        }
        $pg_sig = md5('check;' . $joined . self::SECRET_KEY);
        # end count

        $simple_xml = new \SimpleXMLElement($xml_str);
        $simple_xml->pg_salt = $params['pg_salt'];
        $simple_xml->pg_sig = $pg_sig;
        $simple_xml->pg_description = $message;

        return $simple_xml;
    }

    private function prepareRequest(Order $order)
    {
        $params = new \stdClass();
        $params->pg_merchant_id = self::MERCHANT_ID;
        $params->pg_order_id = $order->getId();
        $params->pg_amount = $order->getTotal();
        $params->pg_currency = 'RUB';

        $params->pg_check_url = self::API_URL . '/platron/check';
        $params->pg_result_url = self::API_URL . '/platron/result';

        $params->pg_request_method = 'XML';

        # put your attention here. Success url works after Platron redirects the browser.
        $params->pg_success_url = self::BASE_URL . '/payment/success';
        $params->pg_success_url_method = 'AUTOGET';

        $params->pg_failure_url = self::BASE_URL . '/payment/failure';
        $params->pg_failure_url_method = 'GET';

        $params->pg_description = "Оплата подписки. Заказ №" . $order->getId();

        $params->pg_testing_mode = 1;

        $this->salt = $this->generateSalt();
        $params->pg_salt = $this->salt;

        # count signature
        $params = (array) $params;
        ksort($params, SORT_STRING);

        $joined = '';
        foreach ($params as $value) {
            $joined .= $value . ';';
        }
        $pg_sig = md5('init_payment.php;' . $joined . self::SECRET_KEY);
        # end count

        $params['pg_sig'] = $pg_sig;

        return $params;
    }

    private function sendRequest(array $params)
    {
        $ch = curl_init();
        $query = http_build_query($params);
        $options = [
            CURLOPT_URL => self::HOST_TO_HOST,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $query,
        ];

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    private function simpleValidateInit(\SimpleXMLElement $xml)
    {
        $condition = (string) $xml->pg_status == 'ok'
            && ( (string) $xml->pg_salt == $this->salt)
            && filter_var((string) $xml->pg_redirect_url, FILTER_VALIDATE_URL) !== FALSE
            && filter_var((string) $xml->pg_payment_id, FILTER_VALIDATE_INT) !== FALSE
        ;

        return $condition;
    }

    private function generateSalt()
    {
        return md5(time());
    }
}
