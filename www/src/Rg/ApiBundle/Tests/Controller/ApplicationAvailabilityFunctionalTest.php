<?php
/**
 * Created by PhpStorm.
 * User: sergei
 * Date: 07.12.17
 * Time: 11:30
 */

namespace Rg\ApiBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @param $url
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = static::createClient();

        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return [
            ['/'],
            ['/abyrvalg'], //какой-нибудь левый урл, нормально ведущий к SPA
            ['/api/'],
            ['/api/cart/'],
            ['/api/products/'],
            ['/api/patria/'],
        ];
    }
}