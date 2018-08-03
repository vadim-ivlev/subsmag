<?php

namespace Rg\ApiBundle\Command;

use Rg\ApiBundle\Entity\Notification;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Patritem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
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
    const FROM = ['subsmag@rg.ru' => 'Российская газета'];

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

            $to = [$order->getEmail() => $order->getName()];

            $params = [
                'order' => $order,
                'goods' => $this->concatGoods($order),
            ];

            $subject = "Не забудьте оплатить подписку. Номер заказа " . $order->getId();

            $payment_type = $order->getPayment()->getName();
            switch ($payment_type) {
                case 'platron':
                    $body = $container->get('templating')->render('@RgApi/Emails/order/created/platron.html.twig', $params);
                    break;
                case 'receipt':
                    $params['permalink'] = $this->generateUrl($order);
                    $body = $container->get('templating')->render('@RgApi/Emails/order/created/receipt.html.twig', $params);
                    break;
                case 'invoice':
                    $params['permalink'] = $this->generateUrl($order);
                    $body = $container->get('templating')->render('@RgApi/Emails/order/created/invoice.html.twig', $params);
                    break;
                default:
                    $notification->setState('error');
                    $notification->setError('Unknown payment type.');
                    $em->persist($notification);
                    $em->flush();
                    return true;
            }

            $from = [ $container->getParameter('from_email') => $container->getParameter('from_name') ];
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

        $swift_sender = function (Notification $notification) use ($em, $output, $container) {
            $transport = new \Swift_SendmailTransport('/usr/sbin/sendmail -bs');
            $mailer = new \Swift_Mailer($transport);

            // Create message
            $order = $notification->getOrder();

            $subject = 'Подписка оформлена. Номер заказа '. $order->getId();

            $to = [$order->getEmail() => $order->getName()];

            $params = [
                'order' => $order,
                'goods' => $this->concatGoods($order),
            ];

            $body = $container->get('templating')->render('@RgApi/Emails/order/paid/common.html.twig', $params);

            $from = [ $container->getParameter('from_email') => $container->getParameter('from_name') ];
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

        $generator = $container->get('router')->getGenerator();

        if (!is_null($order->getLegal())) {
            $name = 'rg_api_get_invoice_by_order';
        } else {
            $name = 'rg_api_get_receipt_by_order';
        }

        $host = $container->getParameter('host');
        $scheme = $container->getParameter('scheme');

        $url = join('', [
            $scheme . '://' . $host,
            $generator->generate(
                $name,
                ['enc_id' => $container->get('rg_api.encryptor')->encryptOrderId($order->getId())]
            ),
        ]);

        return $url;
    }

    private function concatGoods(Order $order)
    {
        /** @var Container $container */
        $container = $this->getContainer();

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

        return $goods;
    }
}