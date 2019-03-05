<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer/contact", name="mailer_contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $name = $request->get('name');
        $from = $request->get('email');
        $message = $request->get('message');

        if (!empty($name) || !empty($from) || !empty($message)) {
            $email = (new \Swift_Message('Kontakt od '. $name))
                ->setFrom($from)
                ->setTo('info@zawodowe-online.pl')
                ->setBody(
                    $message
                )
            ;

            $mailer->send($email);
            $this->addFlash('success', 'Wiadomość została pomyślnie wysłana');
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer.'#contact');
    }
}
