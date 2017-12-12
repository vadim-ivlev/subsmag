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
            '[{"id":',
            $client->getResponse()->getContent()
        );

    }

    /**
     * @dataProvider promocodeProvider
     * @depends testListAction
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
                '/api/promos/1',
                '{"error":"Акция не найдена или неактивна."}',
            ],
        ];
    }

    /**
     * @depends testGetOneByCodeAction
     */
    public function testGoodGetOneByCodeAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/promos/' . $_ENV['valid_promocode_id']);
        $this->assertContains(
            '{"id":',
            $client->getResponse()->getContent()
        );
    }
}
