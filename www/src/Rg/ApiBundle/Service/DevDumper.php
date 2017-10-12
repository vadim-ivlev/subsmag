<?php
/**
 * Dumps variables when you have CORS enabled
 * User: sergei
 * Date: 17.07.17
 * Time: 17:19
 */

namespace Rg\ApiBundle\Service;


use Symfony\Component\HttpFoundation\Response;

class DevDumper
{
    public function varDump($var) {
        ob_start();

        echo "<pre>";
        var_dump($var);
        echo "</pre>";

        $content = ob_get_clean();

        return new Response($content);
    }
    public function symDump($var) {
        ob_start();

        echo "<pre>";
        dump($var);
        echo "</pre>";

        $content = ob_get_clean();

        return new Response($content);
    }
}