<?php

namespace Rg\ApiBundle\Command;

use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Notification;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Patritem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class XMLMaterialParserCommand
 * @package XMLLogBundle\Command
 */
class MailSenderCommand extends ContainerAwareCommand
{
    const HOST = 'rg.ru/subsmag';
    const SCHEME = 'https';

    // для локальной разработки, можно удалить
//    const HOST = 'subsmag.loc';
//    const SCHEME = 'http';

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
        $this->printHeader($output);

        $this->sendCreated($output);
        $this->sendPaid($output);

        $output->writeln("Done!");

        return;
    }

    private function sendCreated(OutputInterface $output)
    {
        /** @var Container $container */
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();

        $notifications = $doctrine
            ->getRepository('RgApiBundle:Notification')
            ->getQueueOfCreated()
        ;

        //TODO: filter invalid email addresses

        $swift_sender = function (Notification $notification) use ($em, $output, $container) {
            $transport = new \Swift_SendmailTransport('/usr/sbin/sendmail -bs');
            $mailer = new \Swift_Mailer($transport);

            // Create a message
            $order = $notification->getOrder();


            $from = ['subsmag@rg.ru' => 'Российская газета'];
            $to = [$order->getEmail() => $order->getName()];

            $payment_type = $order->getPayment()->getName();

            // список позиций
            $goods = [];

            foreach ($order->getItems() as $item) {
                $name = $container->get('rg_api.item_name')->form($item);

                $goods[] = [
                    'name' => $name,
                    'qty' => $item->getQuantity(),
                    'cost' => $item->getCost() * $item->getQuantity(),
                ];
            }

            /** @var Patritem $patritem */
            foreach ($order->getPatritems() as $patritem) {
                $patriff = $patritem->getPatriff();
                $name = "Родина №" . $patriff->getIssue()->getMonth() . "'" . $patriff->getIssue()->getYear();

                $goods[] = [
                    'name' => $name,
                    'qty' => $patritem->getQuantity(),
                    'cost' => $patritem->getCost() * $patritem->getQuantity(),
                ];
            }

            $params = [
                'order' => $order,
                'goods' => $goods,
            ];

            $subject = "Не забудьте оплатить подписку. Номер заказа " . $order->getId();

            switch ($payment_type) {
                case 'platron':
                    $body = $container->get('templating')->render('RgApiBundle:Emails:order_created_platron.html.twig', $params);
                    break;
                case 'receipt':
                    $params['permalink'] = $this->generateUrl($order);
                    $body = $container->get('templating')->render('RgApiBundle:Emails:order_created_receipt.html.twig', $params);
                    break;
                case 'invoice':
                    $params['permalink'] = $this->generateUrl($order);
                    $body = $container->get('templating')->render('RgApiBundle:Emails:order_created_invoice.html.twig', $params);
                    break;
                default:
                    $notification->setState('error');
                    $notification->setError('Unknown payment type.');
                    $em->persist($notification);
                    $em->flush();
                    return true;
            }

            $message = (new \Swift_Message($subject))
                ->setFrom($from)
                ->setTo($to)
                ->setBody($body, 'text/html')
            ;

            $output->writeln($message->getBody());

            // send an email
            $result = $mailer->send($message);

            // output result to console
            $output->writeln("Sent $result messages to " . $order->getEmail());

            // записать результат отправки
            if ($result) {
                $notification->setState('sent');
            } else {
                $notification->setState('error');
                $notification->setError('Email sending failed.');
            }

            $em->persist($notification);
            $em->flush();

            return true;
        };

        array_walk(
            $notifications,
            $swift_sender
        );
    }

    private function sendPaid(OutputInterface $output)
    {
        /** @var Container $container */
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();

        $notifications = $doctrine
            ->getRepository('RgApiBundle:Notification')
            ->getQueueOfPaid()
        ;

        $swift_sender = function (Notification $notification) use ($em, $output) {
            $transport = new \Swift_SendmailTransport('/usr/sbin/sendmail -bs');
            $mailer = new \Swift_Mailer($transport);

            // Create a message
            $order = $notification->getOrder();

            $subject = 'Заказ №'. $order->getId() . ' оплачен';

            $from = ['subsmag@rg.ru' => 'Отдел подписки Российской газеты'];
            $to = [$order->getEmail() => $order->getName()];

            $body = [];
            $body[] = 'Уважаемая (уважаемый) ' . $order->getName() . '!';
            $body[] = '';
            $body[] = 'Заказ №' . $order->getId() . ' оплачен.';

            $body_text = join("\r\n", $body);

            $message = (new \Swift_Message($subject))
                ->setFrom($from)
                ->setTo($to)
                ->setBody($body_text)
            ;

            $output->writeln($message->getBody());

            // send an email
            $result = $mailer->send($message);

            // output result to console
            $output->writeln("Sent $result messages to " . $order->getEmail());

            // записать результат отправки
            if ($result) {
                $notification->setState('sent');
            } else {
                $notification->setState('error');
                $notification->setError('Email sending failed.');
            }

            $em->persist($notification);
            $em->flush();
        };

        array_walk(
            $notifications,
            $swift_sender
        );
    }

    private function printHeader(OutputInterface $output)
    {
        $format = "Y-m-d H:i:s";

        $output->writeln("");
        $output->writeln("**************************************");
        $output->writeln("**        SUBSMAG EMAIL SENDER      **");
        $output->writeln("**       " . date($format) . "      **");
        $output->writeln("**************************************");
        $output->writeln("");
    }

    private function generateUrl(Order $order)
    {
        $container = $this->getContainer();

        $context = $container->get('router')->getContext();
        $context->setHost(self::HOST);
        $context->setScheme(self::SCHEME);

        $generator = $this->getContainer()->get('router')->getGenerator();
        $generator->setContext($context);

        if (!is_null($order->getLegal())) {
            $name = 'rg_api_get_invoice_by_order';
        } else {
            $name = 'rg_api_get_receipt_by_order';
        }

        $url = join('', [
            'https://rg.ru/subsmag',
            $generator->generate(
                $name,
                ['enc_id' => $container->get('rg_api.encryptor')->encryptOrderId($order->getId())]
            ),
        ]);

        return $url;
    }
}