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
     * @depends testIndex
     */
    public function testAddAction()
    {
        $client = static::createClient();

        $content = '
        {
            "products": [
                {
                    "first_month": 1,
                    "year": 2018,
                    "duration": 12,
                    "tariff": 110,
                    "quantity": 13
                }
            ],
            "archives": [
                {"quantity": 1, "patriff": 247}
            ]
        }
        ';
        $client->request('PATCH', '/api/cart/', [], [], [], $content);

        $response = $client->getResponse()->getContent();
        $dec = new \ArrayObject(json_decode($response));
        $this->assertThat(
            $dec,
            $this->logicalAnd(
                $this->arrayHasKey(
                    'products'
                ),
                $this->arrayHasKey(
                    'archives'
                )
            )
        );
        $this->assertContains(
            'discounted_price',
            $response
        );
    }

    /**
     * @depends testAddAction
     */
    public function testEmptyAction()
    {
        $client = static::createClient();

        $client->request('PUT', '/api/cart/', [], [], [], '');

        $response = $client->getResponse()->getContent();

        $this->assertEquals(
            '{"products":[],"archives":[]}',
            $response
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

    /**
     * @depends testApplyPromo
     */
    public function testLocPromo()
    {
        $client = static::createClient();

        $uri = '/api/promo/';
        $i = 0;
        while ($i < 201) {
            $client->request('POST', $uri, [], [], [], '{"promocode":"abyrvalg"}');
            $i++;
        }
        $this->assertEquals(
            '{"error":"Подождите, пожалуйста, 30 секунд."}',
            $client->getResponse()->getContent()
        );
    }

    public function promocodeProvider()
    {
        $have_you_questions = ' Есть вопросы? Позвоните нам 8(800)100-11-13, звонок бесплатный по России.';
        return [
            [
                '/api/promo/',
                '{}',
                '{"error":"no promocode given"}',
            ],
            [
                '/api/promo/',
                '{"promocode":"abyrvalg"}',
                '{"error":"Промокод не найден.'.$have_you_questions.'"}',
            ],
            [
                '/api/promo/',
                '{"promocode":"&(byrvalg"}',
                '{"error":"Промокод содержит недопустимые символы. Проверьте, пожалуйста, ещё раз строку.'.$have_you_questions.'"}',
            ],
            [
                '/api/promo/',
                '{"promocode":"' . $_ENV['pinless_promo'] . '"}',
                '{"error":"Пин-код промокода не передан.'.$have_you_questions.'"}',
            ],
            [
                '/api/promo/',
                '{"promocode":"' . $_ENV['pinless_promo'] . '/"}',
                '{"error":"Пин-код промокода не передан или неправильный.'.$have_you_questions.'"}',
            ],
            [
                '/api/promo/',
                '{"promocode":"' . $_ENV['pinless_promo'] . '/voopycoldberg"}',
                '{"error":"Пин-код не найден.'.$have_you_questions.'"}',
            ],
            [
                '/api/promo/',
                '{"promocode":"' . $_ENV['pinless_promo'] . '/' . $_ENV['activated_pin'] . '"}',
                '{"error":"Этот пин-код уже был использован при оформлении подписки на нашем сайте.'.$have_you_questions.'"}',
            ],
            [
                '/api/promo/',
                '{"promocode":"' . $_ENV['non_active_promo'] . '"}',
                '{"error":"Промокод не активен.'.$have_you_questions.'"}',
            ],
        ];
    }
}
