<?php

namespace Rg\ApiBundle\Command;

use Doctrine\ORM\QueryBuilder;
use Rg\ApiBundle\Entity\Notification;
use Rg\ApiBundle\Entity\Order;
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
    const HOST = 'subsmag.rg.ru';
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

        /** @var Container $container */
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();

        $notifications = $doctrine
            ->getRepository('RgApiBundle:Notification')
            ->getQueueOfCreated()
        ;

        $swift_sender = function (Notification $notification) use ($em, $output) {
            $transport = new \Swift_SendmailTransport('/usr/sbin/sendmail -bs');
            $mailer = new \Swift_Mailer($transport);

            // Create a message
            $order = $notification->getOrder();

            $subject = 'Заказ №'. $order->getId() . ' создан';

            $from = ['subsmag@rg.ru' => 'Отдел подписки Российской газеты'];
            $to = [$order->getEmail() => $order->getName()];
//            $from = 'subsmag@rg.ru';
//            $to = $order->getEmail();

            $body = [];
            $body[] = 'Уважаемая (уважаемый) ' . $order->getName() . '!';
            $body[] = '';
            $body[] = 'Ваш заказ №' . $order->getId() . ' создан.';
            $payment_type = $order->getPayment()->getName();

            if ($payment_type == 'platron') {
                $body[] = 'Вы выбрали для оплаты сервис Платрон. Пожалуйста, следуйте инструкциям на сайте Platron.';
            }
            if ($payment_type == 'receipt') {
                $body[] = 'Вы выбрали оплату по банковской квитанции.';
                $body[] = 'Образец квитанции вы можете получить по ссылке ';
                $body[] = $this->generateUrl($order);
            }
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

/*
        $php_mail_sender = function (Notification $notification) use ($em, $output) {
            // Create a message
            $order = $notification->getOrder();

            $subject = 'Заказ №'. $order->getId() . ' создан';
            $from = 'subsmag@rg.ru';
            $to = $order->getEmail();

            $body = [];
            $body[] = 'Уважаемая (уважаемый) ' . $order->getName() . '!';
            $body[] = '';
            $body[] = 'Ваш заказ №' . $order->getId() . ' создан.';
            $payment_type = $order->getPayment()->getName();

            if ($payment_type == 'platron') {
                $body[] = 'Вы выбрали для оплаты сервис Платрон. Пожалуйста, следуйте инструкциям на сайте Platron.';
            }
            if ($payment_type == 'receipt') {
                $body[] = 'Вы выбрали оплату по банковской квитанции.';
                $body[] = 'Образец квитанции вы можете получить по ссылке ';
                $body[] = $this->generateUrl($order);
            }
            $body_text = join("\r\n", $body);

            $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n";

            // send an email
            $result = mail($to, $subject, $body_text, $headers);

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
*/

        array_walk(
            $notifications,
            $swift_sender
        );

        $output->writeln("Done!");

        return;
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

        $url = $generator->generate(
            'rg_api_get_receipt_by_order',
            ['enc_id' => $container->get('rg_api.encryptor')->encryptOrderId($order->getId())],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $url;
    }
}