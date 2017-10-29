<?php

namespace Rg\ApiBundle\Command;

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
class RedisTestCommand extends ContainerAwareCommand
{
    /**
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('subsmag:redistest')
            ->setDescription('I have been made to be a redis probe.')
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
        $this->printHeader($output);

        /** @var Container $container */
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $r = $container->get('snc_redis.default');

        $r->set('test:1:string', "my binary " . date("Y-m-d H:i:s"));

        $t1 = 'test:1:string';
        $output->writeln($r->get($t1));
        $output->writeln($r->type('test:1:string'));
        $output->writeln($r->set('test:1:vlaue', "487"));
        $output->writeln($r->rename('test:1:vlaue', 'test:1:value'));
        $output->writeln($r->exists('test:1:vlaue'));
        $output->writeln($r->exists('test:1:value'));
        $output->writeln($r->keys('test:1:*'));
        $output->writeln($r->del(['test:1:value']));
        $output->writeln($r->keys('test:1:*'));

        $t2 = 'test:2:string';
        $output->writeln($r->set($t2, "Hola, "));
        $output->writeln($r->append($t2, "Max!"));
        $output->writeln($r->get($t2));
        $output->writeln($r->strlen($t2));
        $output->writeln($r->getrange($t2, 5, $r->strlen($t2)));
        $output->writeln($r->setrange($t2, 5, 'abyrvalg!??...'));
        $output->writeln($r->get($t2));

        $t3 = 'test:3:int';
        $output->writeln($r->set($t3, 4));
        $output->writeln($r->incr($t3));
        $output->writeln($r->get($t3));
        $output->writeln($r->incrby($t3, 15));
        $output->writeln($r->get($t3));
        $output->writeln($r->incrbyfloat($t3, 2/3));
        $output->writeln($r->get($t3));
        $output->writeln($r->incrbyfloat($t3, -2/3));
        $output->writeln($r->get($t3));
        $output->writeln($r->decr($t3));
        $output->writeln($r->get($t3));
        $output->writeln($r->decrby($t3, 13));
        $output->writeln($r->get($t3));

        $output->writeln($t4 = ' >>>start!!!'.'test:4:list');
        $output->writeln($r->rpush($t4, [$t1]));
        $output->writeln($r->rpush($t4, [$t2, $t3]));
        $output->writeln($r->lrange($t4, 0, $r->llen($t4)));
//        $output->writeln($r->lrange($t4, 0, $r->llen($t3)));
        $output->writeln('>>> cicle lpop<<<');
        while ($l = $r->lpop($t4)) {
            $output->writeln($l);
        }
        $output->writeln('>>> cicle lpush - rpop<<<');
        $output->writeln($r->lpush($t4, [$t2, $t3]));
        $output->writeln($r->lrange($t4, 0, $r->llen($t4)));
        while ($l = $r->rpop($t4)) {
            $output->writeln($l);
        }

        $output->writeln($t5 = 'test:5:zset');
        $output->writeln($r->zadd($t5, ['vasya' => 1985]));
        $output->writeln($r->zadd($t5, ['petya' => 1987]));
        $output->writeln($r->zadd($t5, ['rimma' => 1967]));
        $output->writeln($r->zrange($t5, 0, -1));

        foreach ($r->zrange($t5, 0, -1) as $man) {
            var_dump($man);
        }


        $output->writeln('');
        $output->writeln("Done!");


        return;
    }

    private function printHeader(OutputInterface $output)
    {
        $format = "Y-m-d H:i:s";

        $output->writeln("");
        $output->writeln("**************************************");
        $output->writeln("**        SUBSMAG REDIS TESTER      **");
        $output->writeln("**       " . date($format) . "      **");
        $output->writeln("**************************************");
        $output->writeln("");
    }
}