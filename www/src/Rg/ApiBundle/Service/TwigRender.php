<?php

namespace Rg\ApiBundle\Service;

use Symfony\Component\HttpFoundation\Response;

class TwigRender
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render() {
        $params = [];

        return $this->twig->render(
            'RgApiBundle:Emails:order_created.html.twig',
            $params
        );
    }
}