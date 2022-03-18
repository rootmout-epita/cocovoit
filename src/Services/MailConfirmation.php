<?php
/**
 * Created by PhpStorm.
 * User: PierreK
 * Date: 12/04/2019
 * Time: 10:16
 */

namespace App\Services;


use App\Entity\EmailChecker;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailConfirmation
{
    private $twig;
    private $mailer;

    public function __construct(Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function sendMail(EmailChecker $checker)
    {
            $message = (new \Swift_Message('Confirmation E-Mail'))
                ->setFrom('noreply-cocovoit@lab.kelbert.fr')
                ->setTo($checker->getMail())
                ->setBody(
                    $this->twig->render('mail/confirmMail.html.twig', [
                        "checker" => $checker
                    ]),
                    'text/html'
                );

        $this->mailer->send($message);
    }
}
