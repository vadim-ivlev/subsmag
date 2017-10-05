<?php

namespace Rg\ApiBundle\Command;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class XMLMaterialParserCommand
 * @package XMLLogBundle\Command
 */
class MailSenderCommand extends ContainerAwareCommand
{
    /**
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('subsmag:mailsender')
            ->setDescription('I send notification and information emails about orders.')
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
        $format = "Y-m-d H:i:s";

        /** @var Container $container */
        $container = $this->getContainer();

        $output->writeln("");
        $output->writeln("**************************************");
        $output->writeln("**        SUBSMAG EMAIL SENDER      **");
        $output->writeln("**       " . date($format) . "      **");
        $output->writeln("**************************************");
        $output->writeln("");

        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->createQueryBuilder();
        $qb->select('u')->from('AccountBundle:User', 'u')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->like('u.email', ':e')
//                    ,$qb->expr()->like('u.email', ':c')
                )
            )
            ->setParameter('e', '%100500%')
        ;
        $u = $qb->getQuery()->getResult();

        //
        $summaries = $doctrine
            ->getRepository('RgApiBundle:Summary')
            ->findAll()
        ;

        // внести и записать изменения
        $em = $doctrine->getManager();
        array_walk(
            $summaries,
            function (Summary $summary) use ($em)
            {
                $summary->setTitle($this->getRandomPhrase());
                $summary->setText($this->getRandomPhrase() . $this->getRandomPhrase() . $this->getRandomPhrase());
                $summary->setPage(mt_rand(1,205));

                $em->persist($summary);

                $em->flush();

                return $summary;
            }
        );


        $output->writeln("Done!");

    }
}