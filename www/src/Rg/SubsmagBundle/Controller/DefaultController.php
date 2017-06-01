<?php

namespace Rg\SubsmagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('RgSubsmagBundle:Default:index.html.twig');
    }
}
