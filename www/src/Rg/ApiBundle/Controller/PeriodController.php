<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Interval;
use Rg\ApiBundle\Entity\Period;
use Rg\ApiBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Response;

class PeriodController extends Controller
{

    public function indexAction()
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $periods = $em->getRepository('RgApiBundle:Period')->findAll();

        if (!$periods) {
            $arrError = [
                'status' => "error",
                'description' => 'Периоды не найдены!',
            ];
            return $out->json($arrError);
        }

        $normalized = array_map([$this, 'getIntervalsAndConvertToArray'], $periods);

        $response = $out->json((object) $normalized);

        return $response;
    }

    public function showAction($id)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $period = $em->getRepository('RgApiBundle:Period')->find($id);

        if (!$period) {
            $arrError = [
                'status' => "error",
                'description' => 'Период не найден!',
            ];
            return $out->json($arrError);
        }

        $prod = $this->getIntervalsAndConvertToArray($period);

        $response = $out->json((object) $prod);

        return $response;
    }

    private function getIntervalsAndConvertToArray(Period $period) {
        $intervals = array_map(
            function(Interval $interval) {
                return [
                    'start' => $interval->getStart(),
                    'end' => $interval->getEnd(),
                ];
            },
            iterator_to_array($period->getIntervals())
        );

        return [
            'id' => $period->getId(),
            'month_start' => $period->getMonthStart(),
            'year_start' => $period->getYearStart(),
            'duration' => $period->getDuration(),
            'intervals' => $intervals,
        ];
    }

    public function createAction(Request $request)
    {
        return (new Out())->json((object) ['ask' => 'wait for a while, please.']);
    }

    public function editAction($id, Request $request)
    {
        return (new Out())->json((object) ['ask' => 'wait for a while, please.']);
    }

}
