<?php

namespace Rg\ApiBundle\Service;


use Doctrine\Common\Collections\Collection;
use Monolog\Logger;
use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Patritem;
use Rg\ApiBundle\Exception\PlatronException;

class Platron
{
    // тестовый магаз, взятый с библиотеки
//    const MERCHANT_ID = 8888;
//    const SECRET_KEY = 'xywoxelepejepowi';

    const MERCHANT_ID = 3716;
    const SECRET_KEY = 'wowakedalapadasy';

    private $salt;
    private $salt1;

    const HOST_TO_HOST = 'https://www.platron.ru/init_payment.php';
    const RECEIPT_CREATE = 'https://www.platron.ru/receipt.php';
    const RECEIPT_STATUS = 'https://www.platron.ru/get_receipt_status.php';

    private $logger;
    private $sighelper;
    private $itemNamer;
    private $calculator;

    private $base_url;
    private $api_url;

    public function __construct(
        Logger $logger,
        SigHelper $sighelper,
        ItemName $itemNamer,
        ProductCostCalculator $calculator,
        $base_url
    )
    {
        $this->logger = $logger;
        $this->sighelper = $sighelper;
        $this->itemNamer = $itemNamer;
        $this->calculator = $calculator;
        $this->base_url = $base_url;

        $this->api_url = $base_url . '/api';
    }

    /**
     * @param Order $order
     * @return \SimpleXMLElement
     * @throws PlatronException
     * @throws \Exception
     */
    public function getReceiptState(Order $order)
    {
        $request_xml = $this->prepareGetReceiptStatusRequest($order);
        $resp = $this->sendRequest(['pg_xml' => $request_xml->asXML()], self::RECEIPT_STATUS);

        $this->logger->info($request_xml->asXML());

        try {
            $xml = new \SimpleXMLElement($resp);
        } catch (\Exception $e) {
            $message = [
                '<- Error, Response for order ',
                $order->getId(),
                ', pg_receipt_id ',
                (string) $request_xml->pg_receipt_id,
                ': _',
                $resp,
                "_, >>>",
                $e->getMessage(),
            ];
            $message = join('', $message);
            $this->logger->error($message);

            throw new PlatronException('Receipt status was not received. Unparseable response from Platron.');
        }

        if (!$this->isOkReceiptState($xml)) {
            if (!$this->isPendingReceiptState($xml)) {
                $description = $this->parseErrorOnReceipt($xml);

                $message = 'Not ok response for order ' . $order->getId() . ': ' . $resp;
                $this->logger->error($message);

                throw new PlatronException('Error response from Platron: ' . $description);
            }
        }

        return $xml;
    }

    private function prepareGetReceiptStatusRequest(Order $order)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><request/>');
        $xml->addChild('pg_merchant_id', self::MERCHANT_ID);

        // extract receipt id from platron request
        $receipt_xml = new \SimpleXMLElement($order->getPlatronReceiptCreateXml());
        $xml->addChild('pg_receipt_id', (string) $receipt_xml->pg_receipt_id);

        $this->salt1 = $this->generateSalt();
        $xml->addChild('pg_salt', $this->salt1);

        $xml->addChild(
            'pg_sig',
            $sig = $this->sighelper->makeXml(basename(self::RECEIPT_STATUS), $xml)
        );

        return $xml;
    }

    /**
     * @param string $pg_payment_id
     * @param Order $order
     * @param array $items
     * @param array $patritems
     * @return \SimpleXMLElement
     * @throws PlatronException
     * @throws \Exception
     */
    public function createReceipt(string $pg_payment_id, Order $order, array $items, array $patritems)
    {
        $request_xml = $this->prepareReceiptCreateRequest($pg_payment_id, $order, $items, $patritems);
        $resp = $this->sendRequest(['pg_xml' => $request_xml->asXML()], self::RECEIPT_CREATE);
        $this->logger->info($request_xml->asXML());

        try {
            $xml = new \SimpleXMLElement($resp);
        } catch (\Exception $e) {
            $message = [
                '<- Error, Response for order ',
                 $order->getId(),
                 ', pg_payment_id ',
                 $pg_payment_id,
                 ': _',
                 $resp,
                 "_, >>>",
                 $e->getMessage(),
            ];
            $message = join('', $message);
            $this->logger->error($message);

            throw new PlatronException('Receipt not created. Unparseable response from Platron.');
        }

        if (!$this->isOkReceipt($xml)) {
            $description = $this->parseErrorOnReceipt($xml);

            $message = 'Not ok response for order ' . $order->getId() . ': ' . $resp;
            $this->logger->error($message);

            throw new PlatronException('Error response from Platron: ' . $description);
        }

        return $xml;
    }

    /**
     * @param string $pg_payment_id
     * @param Order $order
     * @return \SimpleXMLElement
     */
    private function prepareReceiptCreateRequest(string $pg_payment_id, Order $order, array $items, array $patritems)
    {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><request/>');
		$xml->addChild('pg_merchant_id', self::MERCHANT_ID);
        $xml->addChild('pg_payment_id', $pg_payment_id);
        $xml->addChild('pg_operation_type', 'payment');

        $this->salt1 = $this->generateSalt();
        $xml->addChild('pg_salt', $this->salt1);

        /** @var Item $item */
        foreach ($items as $item) {
            $item_name = mb_substr($this->itemNamer->form($item), 0, 124);
            ## для каталожной цены
            $discountedCatCost = $item->getDiscountedCatCost();
            if ($discountedCatCost > 0) {
                $pg_items = $xml->addChild('pg_items');
                $pg_items->addChild('pg_label', $item_name . ' кат');
                $pg_items->addChild('pg_price', $discountedCatCost);
                $pg_items->addChild('pg_quantity', $item->getQuantity());
                $pg_items->addChild('pg_vat', 10);
            }

            ## для доставочной
            $discountedDelCost = $item->getDiscountedDelCost();
            if ($discountedDelCost > 0) {
                $pg_items = $xml->addChild('pg_items');
                $pg_items->addChild('pg_label', $item_name . ' дост');
                $pg_items->addChild('pg_price', $discountedDelCost);
                $pg_items->addChild('pg_quantity', $item->getQuantity());
                $pg_items->addChild('pg_vat', 18);
            };
        }
        /** @var Patritem $patritem */
        foreach ($patritems as $patritem) {
            $patriff = $patritem->getPatriff();
            $name = "Родина №" . $patriff->getIssue()->getMonth() . "-" . $patriff->getIssue()->getYear();
            ## для каталожной цены
            $pi_discountedCatCost = $patritem->getDiscountedCatCost();
            if ($pi_discountedCatCost > 0) {
                $pg_items = $xml->addChild('pg_items');
                $pg_items->addChild('pg_label', $name . ' кат');
                $pg_items->addChild('pg_price', $pi_discountedCatCost);
                $pg_items->addChild('pg_quantity', $patritem->getQuantity());
                $pg_items->addChild('pg_vat', 10);
            }

            ## для доставочной
            $pi_discountedDelCost = $patritem->getDiscountedDelCost();
            if ($pi_discountedDelCost > 0) {
                $pg_items = $xml->addChild('pg_items');
                $pg_items->addChild('pg_label', $name . ' дост');
                $pg_items->addChild('pg_price', $pi_discountedDelCost);
                $pg_items->addChild('pg_quantity', $patritem->getQuantity());
                $pg_items->addChild('pg_vat', 18);
            }
        }

        $xml->addChild(
            'pg_sig',
            $sig = $this->sighelper->makeXml(basename(self::RECEIPT_CREATE), $xml)
        );

        return $xml;
    }

    /**
     * @param Order $order
     * @return \SimpleXMLElement
     * @throws PlatronException
     * @throws \Exception
     */
    public function init(Order $order)
    {

        $request = $this->prepareRequest($order);

        $id = $order->getId();
        $resp = $this->sendRequest($request, self::HOST_TO_HOST);

        //$this->logger->info('-> Platron init for order ' . $id . $request['pg_amount'] . ' RUB');

        try {
            $xml = new \SimpleXMLElement($resp);
        } catch (\Exception $e) {
            $message = '<- Error. Response for order ' . $id . ': _' . $resp . "_, >>>" . $e->getMessage();
            $this->logger->error($message);

            # если платрон ответил непонятно чем, попробуем ещё раз.
            $resp = $this->sendRequest($request, self::HOST_TO_HOST);
            //$this->logger->info('-> Bad try. Sent once again: ' . $id );

            try {
                $xml = new \SimpleXMLElement($resp);
            } catch (\Exception $e) {
                $message = '<- Error. Second response for ' . $id . ' failed: _' . $resp . "_, >>>" . $e->getMessage();
                $this->logger->error($message);

                throw new PlatronException('Unparseable response from Platron.');
            }
        }

        if (!$this->isOkInit($xml)) {
            $description = $this->parseErrorOnInit($xml);

            $message = 'Not ok response for order ' . $id . ': ' . $resp;
            $this->logger->error($message);

            throw new PlatronException('Error response from Platron: ' . $description);
        }

        return $xml;
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

        $params->pg_check_url = $this->api_url . '/platron/check';
        $params->pg_result_url = $this->api_url . '/platron/result';

        $params->pg_request_method = 'XML';

        # put your attention here. Success url works after Platron redirects the browser.
        $params->pg_success_url = $this->base_url . '/payment/success';
        $params->pg_success_url_method = 'AUTOGET';

        $params->pg_failure_url = $this->base_url . '/payment/failure';
        $params->pg_failure_url_method = 'GET';

        $params->pg_description = "Оплата подписки. Заказ №" . $order->getId();

        #######
        ## тестовый режим одной транзакции
//        $params->pg_testing_mode = 1;
        #######

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

    private function sendRequest(array $params, string $url)
    {
        $ch = curl_init($url);
        $query = http_build_query($params);
        $options = [
            //CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $query,
        ];

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

	if ($result == false) {
		$result = curl_error($ch);
	}

        curl_close($ch);

        return $result;
    }

    private function isOkInit(\SimpleXMLElement $xml)
    {
        $condition = (string) $xml->pg_status == 'ok'
            && filter_var((string) $xml->pg_redirect_url, FILTER_VALIDATE_URL) !== FALSE
            && filter_var((string) $xml->pg_payment_id, FILTER_VALIDATE_INT) !== FALSE
        ;

        return $condition;
    }

    private function isPendingReceiptState(\SimpleXMLElement $xml)
    {
        $condition = (string) $xml->pg_status == 'ok'
            && (string) $xml->pg_receipt_status == 'pending'
        ;

        return $condition;
    }

    private function isOkReceiptState(\SimpleXMLElement $xml)
    {
        $condition = (string) $xml->pg_status == 'ok'
            && (string) $xml->pg_receipt_status == 'ok'
        ;

        return $condition;
    }

    private function isOkReceipt(\SimpleXMLElement $xml)
    {
        $condition = (string) $xml->pg_status == 'ok'
            && filter_var((string) $xml->pg_receipt_id, FILTER_VALIDATE_INT) !== FALSE
        ;

        return $condition;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return string
     * @throws \Exception
     */
    private function parseErrorOnInit(\SimpleXMLElement $xml)
    {
        $condition = (string) $xml->pg_status == 'error'
            && ( (string) $xml->pg_salt == $this->salt)
        ;
        if (!$condition) {
            $message = 'Unknown error from Platron';
            $this->logger->error($message . ': ' . $xml->asXML());
            throw new \Exception($message);
        }
        return (string) $xml->pg_error_description;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return string
     * @throws \Exception
     */
    private function parseErrorOnReceipt(\SimpleXMLElement $xml)
    {
        $condition = (string) $xml->pg_status == 'error'
//            && ( (string) $xml->pg_salt == $this->salt1)
        ;
        if (!$condition) {
            $message = 'Unknown error from Platron';
            $this->logger->error($message . ': ' . $xml->asXML());
            throw new PlatronException($message);
        }
        return (string) $xml->pg_error_description;
    }

    private function generateSalt()
    {
        return md5(time());
    }
}

