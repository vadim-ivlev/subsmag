<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Periods as Periods;
use Rg\ApiBundle\Entity\Promocodes as Promocodes;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class PeriodsController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $out = new Out();

        //берем параметры из config.yml
        $periodsList = $this->container->getParameter('periods');

        $response = $out->json($periodsList);

        return $response;
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        //берем параметры из config.yml
        $periodsList = $this->container->getParameter('periods');

        if ($periodsList[$id]) {
            $arrOut = [$id => $periodsList[$id]];
        } else {
            //собираем JSON для вывода ошибки
            $arrOut = [
                'status' => "error",
                'description' => 'Период не найден!',
                'id' => null
            ];
        }

        //собираем JSON для вывода
        $response = $out->json($arrOut);

        return $response;
    }

    
}
