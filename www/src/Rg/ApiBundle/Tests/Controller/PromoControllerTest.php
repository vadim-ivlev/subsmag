<?php
/**
 * Created by PhpStorm.
 * User: sergei
 * Date: 11.12.17
 * Time: 19:37
 */

namespace Rg\ApiBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PromoControllerTest extends WebTestCase
{

    public function testListAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/promos/');

        $this->assertContains(
            '[{"name":',
            $client->getResponse()->getContent()
        );

    }

    /**
     * @dataProvider promocodeProvider
     */
    public function testGetOneByCodeAction($uri, $resp)
    {
        $client = static::createClient();

        $client->request('GET', $uri);

        $this->assertEquals(
            $resp,
            $client->getResponse()->getContent()
        );
    }

    public function promocodeProvider()
    {
        return [
            [
                '/api/promos/abyr',
                '{"error":"Акция не найдена или неактивна."}',
            ],
        ];
    }

    public function testGoodGetOneByCodeAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/promos/' . $_ENV['valid_promocode']);
        $this->assertContains(
            '{"name":',
            $client->getResponse()->getContent()
        );
    }
}
