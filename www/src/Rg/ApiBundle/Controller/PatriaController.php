<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Patriff;
use Rg\ApiBundle\Entity\Summary;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

class PatriaController extends Controller
{
    public function indexAction(Request $request)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $from_front_id = $request->query->get('area_id', $this->getParameter('area'));

        $area = $em->getRepository('RgApiBundle:Area')->findOneBy(['id' => $from_front_id]);

        if (!$area) {
            $arrError = [
                'status' => "error",
                'description' => 'Регион не найден.',
            ];
            return $out->json($arrError);
        }

        ### now get deliveries
        $deliveries = $em->getRepository('RgApiBundle:Patriff')->getDistinctDeliveries();

        $deliveries_with_issues_grouped_by_years = array_map(
            function (array $delivery) use ($em, $out, $area) {
                ### first get years, then attach relevant issues to it
                $years = $em->getRepository('RgApiBundle:Patriff')
                    ->getDistinctYearsWithDelivery($delivery['id']);

                if (!$years) {
                    $arrError = [
                        'status' => "error",
                        'description' => 'No years found.',
                    ];
                    return $out->json($arrError);
                }

                $years_with_issues = array_map(
                    function (array $year) use ($em, $delivery, $area) {
                        // для каждого года показать номера
                        $patriff_container = $em->getRepository('RgApiBundle:Patriff')
                            ->findIssuesByYearAndDelivery($year['year'], $delivery['id'], $area->getZone()->getId());

                        $issues_with_summaries = array_map(
                            function (array $issue) {
                                /** @var Patriff $patriff */
                                $patriff = $issue[0];

                                $summaries = $patriff
                                    ->getIssue()
                                    ->getSummaries()
                                    ->map(
                                        function (Summary $summary) {
                                            return [
                                                'id' => $summary->getId(),
                                                'title' => $summary->getTitle(),
                                                'text' => $summary->getText(),
                                                'page' => $summary->getPage(),
                                            ];
                                        }
                                    )
                                    ->toArray()
                                ;

                                $issue['summaries'] = $summaries;

                                $issue['price'] = $patriff->getPrice();

                                return $issue;
                            },
                            $patriff_container
                        );

                        $year['issues'] = $issues_with_summaries;

                        return $year;
                    },
                    $years
                );

                $delivery['years'] = $years_with_issues;

                return $delivery;
            },
            $deliveries
        );

        $patria = $em->getRepository('RgApiBundle:Edition')->findOneBy(['keyword' => 'archive']);

        $container = [
            'id' => $patria->getId(),
            'name' => $patria->getName(),
            'description' => $patria->getDescription(),
            'text' => $patria->getText(),
            'image' => $patria->getImage(),
            'deliveries' => $deliveries_with_issues_grouped_by_years,
        ];

        return  $out->json($container);
    }


}
