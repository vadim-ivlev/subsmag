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
    /**
     * Глубокая свистопляска с Origin связана с желанием разрабатывать фронт локально и
     * с политикой WM как проксера заголовков.
     * Опытным путём выявлено, что WM не отдаёт CORS-заголовок Allow-Origin для портов, отличных от :80
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $responseHeaders = $event->getResponse()->headers;

        $origin = $_SERVER['HTTP_ORIGIN'] ?? null;

        if($this->checkOrigin($origin)) {
//        if($this->checkHost()) {
//        if(false) {
            $responseHeaders->set('Access-Control-Allow-Headers', 'accept, content-type, origin');
//            $responseHeaders->set('Access-Control-Allow-Credentials', 'true');

            $responseHeaders->set('Access-Control-Allow-Origin', $origin);

            $responseHeaders->set('Access-Control-Allow-Methods', 'OPTIONS, POST, GET, PUT, DELETE, PATCH');
        }
    }

    private function checkOrigin($origin)
    {
        $check_origin = ((strpos($origin, 'good.rg.ru:3030') !== false) || (strpos($origin, 'loc.rg.ru:3030') !== false));

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