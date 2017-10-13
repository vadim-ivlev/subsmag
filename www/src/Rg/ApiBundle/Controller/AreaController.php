<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

class AreaController extends Controller
{
    public function getCitiesAction(Request $request)
    {
        $from_front_id = $request->query->get('from_front_id') ?? $this->getFrontId($request);

        if (!$from_front_id) {
            $error = [
                'error' => 'Region not found neither in query string nor in cookie',
            ];

            return (new Out())->json($error);
        }

        $area = $this->getDoctrine()->getRepository('RgApiBundle:Area')
            ->findOneBy(['from_front_id' => $from_front_id]);

        if (!is_null( $area->getParentArea() ) )
            $area = $area->getParentArea();

        $cities = array_map(
            function (City $city) {
                return [
                    'city_id' => $city->getId(),
                    'city_name' => $city->getName(),
                ];
            },
            $area->getCities()->toArray()
        );

        $output = [
            'area' => [
                'id' => $from_front_id,
                'name' => $area->getName(),
            ],
            'cities' => $cities,
        ];

        return (new Out())->json($output);
    }

    private function getFrontId($request): int
    {
        //TODO: works only on prod with cookies
        $rg_geo_data = $request->cookies->get('rg_geo_data') ?? $request->cookies->get('rg_user_region');

        // Test purposes only. Remove!
        // московская кука
//        $rg_geo_data = "%7B%22id%22%3A201%2C%22rgId%22%3A3132%2C%22link%22%3A%22%5C%2Fregion%5C%2Fcfo%5C%2Fstolica%5C%2F%22%2C%22originName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu0430%22%2C%22originPrepositionalName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu0435%22%2C%22originGenitiveName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu044b%22%2C%22rubricName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu0430%22%2C%22rubricPrepositionalName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu0435%22%2C%22rubricGenitiveName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu044b%22%7D";
        // владивостокская кука
//        $rg_geo_data = "%7B%22id%22%3A96%2C%22rgId%22%3A3718%2C%22link%22%3A%22%5C%2Fregion%5C%2Fdfo%5C%2Fprimorie%5C%2Fvladivostok%5C%2F%22%2C%22originName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%22%2C%22originPrepositionalName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%5Cu0435%22%2C%22originGenitiveName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%5Cu0430%22%2C%22rubricName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%22%2C%22rubricPrepositionalName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%5Cu0435%22%2C%22rubricGenitiveName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%5Cu0430%22%7D";

        if (!$rg_geo_data) {
            return 0;
        }

        $from_front_id = (json_decode( urldecode($rg_geo_data) ))->id;

        return $from_front_id;
    }
}
