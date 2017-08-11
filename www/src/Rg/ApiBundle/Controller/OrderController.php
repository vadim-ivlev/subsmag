<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends Controller
{

    public function createAction(Request $request, SessionInterface $session)
    {
        return 'I am going to be finished till August 17th';
    }

}
