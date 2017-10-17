<?php
/**
 * Created by PhpStorm.
 * User: sergei
 * Date: 10.07.17
 * Time: 17:09
 */

namespace Rg\ApiBundle\Listeners;

use Rg\ApiBundle\Exception\OrderException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class OrderExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();

        if ($e instanceof OrderException) {
            $error = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];

            //собираем JSON для вывода
            $response = new JsonResponse();
            $response
                ->setData($error)
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                ->headers->set('Content-Type', 'application/json; charset=utf-8')
            ;

            $response->setStatusCode(Response::HTTP_OK);

            // Send the modified response object to the event
            $event->setResponse($response);
        }
    }

}