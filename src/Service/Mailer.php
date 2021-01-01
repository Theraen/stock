<?php

namespace App\Service;

use Swift_Image;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Twig\Environment;

class Mailer
{

    private $templating;

    public function __construct(Environment $templating)
    {
      $this->templating = $templating;  
    }

    public function send($subject, $emailSender, $emailRecipient, $template, $vars = [])
    {
        $transport = (new Swift_SmtpTransport($_ENV['SMTP_HOST'], $_ENV['SMTP_PORT']))
        ->setUsername($_ENV['SMTP_USERNAME'])
        ->setPassword($_ENV['SMTP_PASSWORD']);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
        ->setFrom($emailSender)
        ->setTo($emailRecipient)
        ->setContentType('text/html');

        if (isset($vars['picture'])) {
            $picture = $message->embed(Swift_Image::fromPath($vars['picture']));
        } else {
            $picture = null;
        }

        $message->setBody(
            $this->templating->render('emails/' . $template . '.html.twig', [
                'vars' => $vars,
                'picture' => $picture
            ])
        );

        return $mailer->send($message);
    }
}