<?php
/**
 * #type: # order_created, order_paid
 * #state: # queued -- в очереди на отправку, sent -- отправлено, failed -- ошибка отправки
 */

namespace Rg\ApiBundle\Service;

use Rg\ApiBundle\Entity\Notification;
use Rg\ApiBundle\Entity\Order;

class NotificationQueue
{
    public function onOrderCreate(Order $order)
    {
        $notification = new Notification();
        $notification->setType('order_created');
        $notification->setState('queued');
        $notification->setDate(new \DateTime());
        $notification->setOrder($order);
        $notification->setError('');

        return $notification;
    }

    public function onOrderPaid(Order $order)
    {
        $notification = new Notification();
        $notification->setType('order_paid');
        $notification->setState('queued');
        $notification->setDate(new \DateTime());
        $notification->setOrder($order);
        $notification->setError('');

        return $notification;
    }
}
