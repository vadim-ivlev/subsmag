<?php
/**
 * Created by PhpStorm.
 * User: sergei
 * Date: 13.12.17
 * Time: 17:47
 */

namespace Rg\ApiBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Rg\ApiBundle\Service\SigHelper;

class SigHelperTest extends TestCase
{

    /**
     * @param $xml
     * @param $scriptName
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testCheckXml($xml, $scriptName, $expected)
    {
        $sh = new SigHelper('wowakedalapadasy');

        $signature = (string) (new \SimpleXMLElement($xml))->pg_sig;

        $result = $sh->checkXml(
            $signature,
            $scriptName,
            $xml
        );

        $this->assertEquals($expected, $result);
    }

    public function dataProvider()
    {
        return [
            [
                'xml' => '<?xml version="1.0" encoding="utf-8"?>
            <request>
            <pg_salt>5a2e335ff33</pg_salt>
            <pg_order_id>1167</pg_order_id>
            <pg_payment_id>37230160</pg_payment_id>
            <pg_card_brand>CA</pg_card_brand>
            <pg_card_pan>548309******9452</pg_card_pan>
            <pg_card_hash>5bf79db8964bf2c1db0f87fd1399d66ff70fac51</pg_card_hash>
            <pg_auth_code>467298</pg_auth_code>
            <pg_captured>0</pg_captured>
            <pg_sig>8973705d16d740601215f1ef02717cdf</pg_sig>
            </request>',
                'scriptName' => 'success',
                'result' => false,
            ],
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
            <pg_sig>78e967553da2f1ca38aef3d202be8ca</pg_sig>
        </request>',
                'scriptName' => 'result',
                'result' => false,
            ],
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
            <pg_sig>078e967553da2f1ca38aef3d202be8ca</pg_sig>
        </request>',
                'scriptName' => 'result',
                'result' => true,
            ],
            [
                'xml' => '<?xml version="1.0" encoding="utf-8"?>
            <request>
            <pg_salt>5a2e32f35ff33</pg_salt>
            <pg_order_id>1167</pg_order_id>
            <pg_payment_id>37230160</pg_payment_id>
            <pg_card_brand>CA</pg_card_brand>
            <pg_card_pan>548309******9452</pg_card_pan>
            <pg_card_hash>5bf79db8964bf2c1db0f87fd1399d66ff70fac51</pg_card_hash>
            <pg_auth_code>467298</pg_auth_code>
            <pg_captured>0</pg_captured>
            <pg_sig>8973705d16d740601215f1ef02717cdf</pg_sig>
            </request>',
                'scriptName' => 'success',
                'result' => true,
            ],
        ];
    }
}
