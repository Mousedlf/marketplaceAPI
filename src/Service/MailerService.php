<?php

namespace App\Service;

use App\Entity\Order;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerService
{
    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function sendAccountDeletionEmail(string $toEmail, string $fromEmail): void
    {
        $htmlContent = $this->twig->render('mailer/layout/user.html.twig', [
            'mail' => $toEmail,
        ]);

        $this->sendEmail($fromEmail, $toEmail, 'Account Deletion', $htmlContent);
    }

    public function sendOrderEmail(string $toEmail, string $fromEmail, Order $order): void
    {
        // for each api -> send mail with keyGenerateService
        //        $htmlContent = $this->twig->render('mailer/layout/key.html.twig', [
        //            'mail' => $toEmail,
        //            'date_order' => $order->getDate(),
        //            'product_name' => $order->getAPIs(),
        //            'offre_name' => $orderDetails['offre_name'],
        //            'product_price' => $order->getTotal(),
        //            'activation_key' => $orderDetails['activation_key'],
        //        ]);

        // $this->sendEmail($fromEmail, $toEmail, 'Order Confirmation', $htmlContent);
    }

    /**
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $subject
     * @param string $htmlContent
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function sendEmail(string $fromEmail, string $toEmail, string $subject, string $htmlContent): void
    {
        $email = (new Email())
            ->from($fromEmail)
            ->to($toEmail)
            ->subject($subject)
            ->html($htmlContent);
        try {
            $this->mailer->send($email);
        } catch (Exception $e) {
            throw new Exception('Failed to send email: ' . $e->getMessage());
        }
    }
}
