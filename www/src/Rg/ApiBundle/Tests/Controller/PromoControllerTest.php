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
}
