<?php

namespace Rg\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;

class CartControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/api/cart/');

        $this->assertContains(
            '{"products":[],"archives":[]}',
            $client->getResponse()->getContent()
        );
    }

    /**
     * @dataProvider promocodeProvider
     */
    public function testApplyPromo($uri, $content, $resp)
    {
        $client = static::createClient();

        $client->getCookieJar()->set(
            new Cookie(
                'cityId',
                '1',
                strtotime('+1 day')
            )
        );

        $client->request('POST', $uri, [], [], [], $content);
        $this->assertEquals(
            $resp,
            $client->getResponse()->getContent()
        );
    }

    public function promocodeProvider()
    {
        return [
            [
                '/api/promo/',
                '{}',
                '{"error":"no promocode given"}',
            ],
            [
                '/api/promo/',
                '{"promocode":"abyrvalg"}',
                '{"error":"Промокод не найден"}',
            ],
            [
                '/api/promo/',
                '{"promocode":"&(byrvalg"}',
                '{"error":"Промокод содержит недопустимые символы. Проверьте, пожалуйста, ещё раз строку."}',
            ],
            [
                '/api/promo/',
                '{"promocode":"asdf123"}',
                '{"error":"Пин-код промокода не передан."}',
            ],
            [
                '/api/promo/',
                '{"promocode":"asdf123/"}',
                '{"error":"Пин-код промокода не передан или неправильный."}',
            ],
            [
                '/api/promo/',
                '{"promocode":"asdf123/voopycoldberg"}',
                '{"error":"Пин-код не найден."}',
            ],
            [
                '/api/promo/',
                '{"promocode":"asdf123/d7ba49"}',
                '{"error":"Пин-код уже активирован."}',
            ],
            [
                '/api/promo/',
                '{"promocode":"salegood"}',
                '{"error":"Промокод не активен."}',
            ],
        ];
    }
}
