<?php

namespace Rg\ApiBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Rg\ApiBundle\Entity\Area;
use Rg\ApiBundle\Entity\City;
use Rg\ApiBundle\Entity\Zone;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MatchCitiesToRegionsCommand extends ContainerAwareCommand
{
    /**
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('city:match')
            ->setDescription('Get regions of cities from work')
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
        ;

        return null;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("");
        $output->writeln("**************************************");
        $output->writeln("**      Cities fetcher              **");
        $output->writeln("**************************************");
        $output->writeln("");

        ########## your code here ##########

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $this->step1($output, $em);
        ### s.2
        ### not yet finished
/*        $cities = $em->getRepository('RgApiBundle:Area')
            ->getCitiesStep2();

        $output->writeln(count($cities));

        $areas = $em->getRepository('RgApiBundle:Area')->getAreaWithNotEmptyWorkIdOrderedByWorkId();

        array_walk(
            $cities,
            function (Area $city) use ($areas, $output, $em) {
                $output->writeln('###');
                $output->writeln($city->getName());

                // Для каждого true-дочернего региона выделить корень из link и найти его в родителях-"area".
                // По найденному родителю установить зону.
                $true_child_entries = array_map(
                    function (array $child) use ($areas) {
                        $link_root = $this->pullRootLink($child['link']);

                        foreach ($areas as $area) {
                            $condition = $area->getLink() == $link_root;
                            if ($condition) {
                                $zone_id = $area->getZone()->getId();
                                break;
                            }
                        }

                        $item = [
                            'works_id' => '',
                            'name' => $child['originName'],
                            'from_front_id' => $child['id'],
                            'zone_id' => $zone_id,
                            'link' => $child['link'],
                        ];

                        return $item;
                    },
                    $true_child_regions
                );

                $output->writeln('###');
                return;
            }
        );*/
        ########## ########## ########## ###

        $output->writeln("Done.");

        return;
    }

    private function fetchArray(string $city_name) {
        $ch = curl_init();
        $query = http_build_query(['region_by_city_name_X' => $city_name]);
        $options = [
            CURLOPT_URL => "https://works.rg.ru/project/subscribe_mag/region_zone.php",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $query,
        ];

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        curl_close($ch);

        try {
            $cities_array = unserialize($result);
        } catch (\Exception $e) {
            print_r($result);
            die;
        }

        if (!is_array($cities_array)) throw new \Exception($result);

        return $cities_array;
    }

    private function step1(OutputInterface $output, ObjectManager $em)
    {
        // some cities have more than one matching region at works.rg.ru
        // let's process first set of them which have only one matching array.
        array_walk(
            $cities,
            function (Area $city) use ($output, $em) {
                $cities_array = $this->fetchArray($city->getName());
                $output->writeln($city->getName());

                if (count($cities_array) == 1) {
                    //
                    $parent_area = $em->getRepository('RgApiBundle:Area')
                        ->findOneBy(['works_id' => $cities_array[0]['region_id']]);
                    if (!$parent_area) return;

                    $output->writeln($parent_area->getName() . ' is set as parent.');

                    $city->setParentArea($parent_area);
                    $em->persist($city);
                    $em->flush();
                    $output->writeln($city->getName() . ' done!');
                } elseif (count($cities_array) > 1) {
                    $output->writeln($city->getName() . ' has more than one region to match.');
                } else $output->writeln('has no match?');
            }
        );
    }
}