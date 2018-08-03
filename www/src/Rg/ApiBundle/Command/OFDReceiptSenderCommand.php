<?php

namespace Rg\ApiBundle\Command;

use Rg\ApiBundle\Entity\Notification;
use Rg\ApiBundle\Exception\PlatronException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class XMLMaterialParserCommand
 * @package XMLLogBundle\Command
 */
class OFDReceiptSenderCommand extends ContainerAwareCommand
{
    const FROM = ['subsmag@rg.ru' => 'Российская газета'];

    /**
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('subsmag:ofdsender')
            ->setDescription('I send receipts containing OFD Platron information.')
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
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->printHeader($output);

        $this->sendOFDReceipt($output);

        $output->writeln("Done!");

        return;
    }

    /**
     * @param OutputInterface $output
     * @throws \Exception
     */
    private function sendOFDReceipt(OutputInterface $output)
    {
        /** @var Container $container */
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();

        $notifications = $doctrine
            ->getRepository('RgApiBundle:Notification')
            ->getQueueOfOFDReceipt()
        ;

        $status_checker_and_sender = function (Notification $notification) use ($em, $output, $container) {
            // сперва проверим статус чека
            $order = $notification->getOrder();
            $platron = $container->get('rg_api.platron');

            try {
                $platron_ofd_receipt_xml = $platron->getOFDReceiptForMailer($order);
            } catch (PlatronException $e) {
                // значит, Платрон по этому заказу и чеку заругался.

                // запишем это в базу
                $notification->setState('error');
                $notification->setError($e->getMessage());

                $em->persist($notification);
                $em->flush();

                // и запишем это в лог
                $message = [
                    'ERROR: platron said bad words for order: ',
                    $order->getId(),
                    ': _',
                    "_, >>>",
                    $e->getMessage(),
                ];
                $message = join('', $message);

                $output->writeln($message);

                // наверное, правильно будет сообщить о такой беде разрабу.
                // если он ещё этого не сделал -- будь добр, запили оповещение.
                return;
            } catch (\Exception $e) {
                $message = [
                    'ERROR: Receipt cannot be received now. Unparseable response from Platron for order: ',
                    $order->getId(),
                    ': _',
                    "_, >>>",
                    $e->getMessage(),
                ];
                $message = join('', $message);

                $output->writeln($message);

                // пока просто возвращаю с надеждой, что Платрон затупил или у нас интернета нет.
                // если вернулся сюда -- может, счётчик таких неудач сделать?
                return;
            }

            if ($platron->isPendingReceiptState($platron_ofd_receipt_xml)) {
                // продолжаем ждать.
                return;
            }

            // теперь отправим письмо с чеком
            $transport = new \Swift_SendmailTransport('/usr/sbin/sendmail -bs');
            $mailer = new \Swift_Mailer($transport);

            // Create message
            $subject = 'Чек. Номер заказа '. $order->getId();

            $to = [$order->getEmail() => $order->getName()];

            $params = $container->get('rg_api.ofd_receipt')
                ->prepareOFDReceiptByOrder($order, $platron_ofd_receipt_xml);

            $body = $container->get('templating')
                ->render(
                    '@RgApi/order/ofd.html.twig',
                    $params
                );

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
            $status_checker_and_sender
        );
    }

    private function printHeader(OutputInterface $output)
    {
        $format = "Y-m-d H:i:s";

        $output->writeln("");
        $output->writeln("**************************************");
        $output->writeln("**        SUBSMAG OFD SENDER      **");
        $output->writeln("**       " . date($format) . "      **");
        $output->writeln("**************************************");
        $output->writeln("");
    }

}