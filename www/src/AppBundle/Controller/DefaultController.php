<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $base_dir = realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR;

        $index_path = $base_dir . 'web/index.html';

        if (!file_exists($index_path)) {
            throw new NotFoundHttpException('SPA not found.');
        }

        return new Response(
            file_get_contents($index_path)
//            json_encode($resp)
        );
    }
}
