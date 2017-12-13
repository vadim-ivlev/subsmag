<?php
/**
 * Created by PhpStorm.
 * User: sergei
 * Date: 13.12.17
 * Time: 18:24
 */

namespace Rg\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlatronControllerTest extends WebTestCase
{
    /**
     * @dataProvider successProvider
     */
    public function testSuccessResultURLAction($xml)
    {
        $client = static::createClient();

        $client->request('POST', '/api/platron/result', ['pg_xml' => $xml], [], []);

        $content = $client->getResponse()->getContent();

        $r_xml = new \SimpleXMLElement($content);

        $this->assertEquals(
            'ok',
            (string) $r_xml->pg_status
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function testResultURLAction($xml, $message)
    {
        $client = static::createClient();

        $client->request('POST', '/api/platron/result', ['pg_xml' => $xml], [], []);

        $content = $client->getResponse()->getContent();

        $r_xml = new \SimpleXMLElement($content);

        $this->assertContains(
            $message,
            (string) $r_xml->pg_error_description
        );
    }

    public function dataProvider()
    {
        return [
            [
                'xml' => '<?xml version="1.0" encoding="utf-8"?>
                    <request>
                    <pg_salt>c0945b1e</pg_salt>
                    <pg_order_id>1167</pg_order_id>
                    <pg_payment_id>37230160</pg_payment_id>
                    <pg_amount>1610.0000</pg_amount>
                    <pg_currency>RUB</pg_currency>
                    <pg_net_amount>1560.09</pg_net_amount>
                    <pg_ps_amount>1610</pg_ps_amount>
                    <pg_ps_full_amount>1610.00</pg_ps_full_amount>
                    <pg_ps_currency>RUB</pg_ps_currency>
                    <pg_payment_system>TINKOFFBANKCARD</pg_payment_system>
                    <pg_result>1</pg_result>
                    <pg_payment_date>2017-12-11 10:25:38</pg_payment_date>
                    <pg_can_reject>1</pg_can_reject>
                    <pg_user_phone>79296283078</pg_user_phone>
                    <pg_need_phone_notification>1</pg_need_phone_notification>
                    <pg_user_contact_email>21svetik85@mail.ru</pg_user_contact_email>
                    <pg_need_email_notification>1</pg_need_email_notification>
                    <pg_card_brand>CA</pg_card_brand>
                    <pg_card_pan>548309******9452</pg_card_pan>
                    <pg_card_hash>5bf79db8964bf2c1db0f87fd1399d66ff70fac51</pg_card_hash>
                    <pg_auth_code>467298</pg_auth_code>
                    <pg_captured>0</pg_captured>
                    <pg_sig>08e967553da2f1ca38aef3d202be8ca</pg_sig>
                    </request>',
                'message' => 'Неправильная подпись',
            ],
            [
                'xml' => '<?xml version="1.0" encoding="utf-8"?>
                    <request>
                    <pg_salt>c045b1e</pg_salt>
                    <pg_order_id>1167</pg_order_id>
                    <pg_payment_id>37230160</pg_payment_id>
                    <pg_amount>1610.0000</pg_amount>
                    <pg_currency>RUB</pg_currency>
                    <pg_net_amount>1560.09</pg_net_amount>
                    <pg_ps_amount>1610</pg_ps_amount>
                    <pg_ps_full_amount>1610.00</pg_ps_full_amount>
                    <pg_ps_currency>RUB</pg_ps_currency>
                    <pg_payment_system>TINKOFFBANKCARD</pg_payment_system>
                    <pg_result>1</pg_result>
                    <pg_payment_date>2017-12-11 10:25:38</pg_payment_date>
                    <pg_can_reject>1</pg_can_reject>
                    <pg_user_phone>79296283078</pg_user_phone>
                    <pg_need_phone_notification>1</pg_need_phone_notification>
                    <pg_user_contact_email>21svetik85@mail.ru</pg_user_contact_email>
                    <pg_need_email_notification>1</pg_need_email_notification>
                    <pg_card_brand>CA</pg_card_brand>
                    <pg_card_pan>548309******9452</pg_card_pan>
                    <pg_card_hash>5bf79db8964bf2c1db0f87fd1399d66ff70fac51</pg_card_hash>
                    <pg_auth_code>467298</pg_auth_code>
                    <pg_captured>0</pg_captured>
                    <pg_sig>078e967553da2f1ca38aef3d202be8ca</pg_sig>
                    </request>',
                'message' => 'Неправильная подпись',
            ],
        ];
    }

    public function successProvider()
    {
        return [
            [
                'xml' => '<?xml version="1.0" encoding="utf-8"?>
                    <request>
                    <pg_salt>24941a3bb</pg_salt>
                    <pg_order_id>546</pg_order_id>
                    <pg_payment_id>36308670</pg_payment_id>
                    <pg_amount>2244.0000</pg_amount>
                    <pg_currency>RUB</pg_currency>
                    <pg_net_amount>2174.44</pg_net_amount>
                    <pg_ps_amount>2244</pg_ps_amount>
                    <pg_ps_full_amount>2244.00</pg_ps_full_amount>
                    <pg_ps_currency>RUB</pg_ps_currency>
                    <pg_payment_system>TINKOFFBANKCARD</pg_payment_system>
                    <pg_result>1</pg_result>
                    <pg_payment_date>2017-10-31 13:21:15</pg_payment_date>
                    <pg_can_reject>1</pg_can_reject>
                    <pg_user_phone>79165022251</pg_user_phone>
                    <pg_need_phone_notification>1</pg_need_phone_notification>
                    <pg_user_contact_email>olga-kondratenko@mail.ru</pg_user_contact_email>
                    <pg_need_email_notification>1</pg_need_email_notification>
                    <pg_card_brand>VI</pg_card_brand>
                    <pg_card_pan>427940******9624</pg_card_pan>
                    <pg_card_hash>d6f84ebc339efdac4d9f527d391b6f0478837256</pg_card_hash>
                    <pg_auth_code>337075</pg_auth_code>
                    <pg_captured>0</pg_captured>
                    <pg_sig>18ec804e76c009ab12f1e1aa81c02222</pg_sig>
                    </request>',
            ],
        ];
    }
}
