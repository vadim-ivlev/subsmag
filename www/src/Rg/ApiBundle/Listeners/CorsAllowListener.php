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

        $origin = $_SERVER['HTTP_ORIGIN'] ?? null;

//        if($this->checkOrigin($origin)) {
//        if($this->checkHost()) {
        if(false) {
            $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
            $responseHeaders->set('Access-Control-Allow-Credentials', 'true');
            $responseHeaders->set('Access-Control-Allow-Origin', $origin);
            $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        }
    }

    private function checkOrigin($origin)
    {

        $check_origin = strpos($origin, 'rg.ru') !== false;

//        $check_origin = strpos($origin, 'subsmag.loc') !== false;

//        $check_origin = true;

        return $check_origin;
    }

    private function checkHost()
    {
        $host = $_SERVER['HTTP_HOST'];

        $me = (strpos($host, 'subsmag.loc') !== false) || (strpos($host, 'dev.rg.ru') !== false);

        return $me;
    }
}