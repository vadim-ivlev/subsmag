<?php

namespace Rg\ApiBundle\Command;

use Rg\ApiBundle\Entity\Area;
use Rg\ApiBundle\Entity\City;
use Rg\ApiBundle\Entity\Zone;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FetchCitiesFromWorksCommand extends ContainerAwareCommand
{
    /**
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('city:fetch')
            ->setDescription('Get cities from work')
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

//        $cities_array = array_values(unserialize($cities));

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $areas = $em->getRepository('RgApiBundle:Area')
            ->getAreaWithNotEmptyWorkIdOrderedByWorkId();

        array_walk(
            $areas,
            function (Area $area) use ($output, $em) {
                $cities_array = $this->fetchArray($area->getWorksId());
                $output->writeln($area->getName());
                $output->writeln( count($cities_array) . " нас.пунктов." );

                array_walk(
                    $cities_array,
                    function (array $works_city) use ($area, $em) {
                        $city = new City();
                        $city->setWorksCid($works_city['cid']);
                        $city->setName($works_city['name']);
                        $city->setType($works_city['socr']);
                        $city->setArea($area);
                        $em->persist($city);
                    }
                );
                $em->flush();

                $output->writeln($area->getName() . ' done!');
            }
        );
        ########## ########## ########## ###

        $output->writeln("Done.");

        return;
    }

    private function fetchArray(string $region_id) {
        $ch = curl_init();
        $query = http_build_query(['parse_rid' => $region_id]);
        $options = [
            CURLOPT_URL => "https://works.rg.ru/project/subscribe_mag/region_zone.php",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $query,
        ];

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        curl_close($ch);

        $cities_array = unserialize($result);

        if (!is_array($cities_array)) throw new \Exception('bad array given for region ' . $region_id);

        return $cities_array;
    }
}