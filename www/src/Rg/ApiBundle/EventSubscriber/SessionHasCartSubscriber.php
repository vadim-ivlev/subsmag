<?php
/**
 * Created by PhpStorm.
 * User: gugenot
 * Date: 14.08.17
 * Time: 21:04
 */

namespace Rg\ApiBundle\EventSubscriber;


use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Controller\SessionHasCartController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SessionHasCartSubscriber implements EventSubscriberInterface
{

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $session = $event->getRequest()->getSession();

        if (!is_array($controller)) return;

        if ($controller[0] instanceof SessionHasCartController) {
            //check if session has 'cart' key yet

            if ($session->has('cart') === false) {
                $cart = new Cart();

                $session->set('cart', serialize($cart));
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}