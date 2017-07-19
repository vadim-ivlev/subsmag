<?php
/**
 * Created by PhpStorm.
 * User: sergei
 * Date: 10.07.17
 * Time: 17:09
 */

namespace Rg\ApiBundle\Listeners;


use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CorsAllowListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $responseHeaders = $event->getResponse()->headers;

        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : NULL;

//        $check_origin = strpos($origin, 'rg.ru') !== false;
        $check_origin = true;
        //TODO: Sergei Barsuk

        if($check_origin) {
            $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
            $responseHeaders->set('Access-Control-Allow-Credentials', 'true');
            $responseHeaders->set('Access-Control-Allow-Origin', $origin);
            $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        }
    }

}