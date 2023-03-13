<?php

namespace App\Service;

use App\Entity\Decision;
use App\Entity\OngoingDecision;
use App\Repository\AssociatedRepository;
use App\Repository\DecisionRepository;
use App\Repository\OngoingDecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSender extends AbstractController
{
    public MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function emailForUserExpert(string $userEmail, string $firstName, string $lastName, string $slug): void
    {
        $mail = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to($userEmail)
            ->subject($lastName . ' ' . $firstName . ', pensez a donné votre avis sur la nouvelle décision')
            ->html($this->renderView('email/emailForExpert.html.twig', [
                'userEmail' => $userEmail,
                'userFirstName' => $firstName,
                'userLastName' => $lastName,
                'linkDecision' => $slug
            ]));
        $this->mailer->send($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function emailForUserImpacted(string $userEmail, string $firstName, string $lastName, string $slug): void
    {
        $mail = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to($userEmail)
            ->subject($lastName . ' ' . $firstName . ', pensez a commenter la nouvelle décision')
            ->html($this->renderView('emailDelayDecision.html.twig', [
                'userEmail' => $userEmail,
                'userFirstName' => $firstName,
                'userLastName' => $lastName,
                'linkDecision' => $slug
            ]));
        $this->mailer->send($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function emailWhenSomeoneComments(string $userEmail, string $firstName, string $lastName, string $slug): void
    {
        $mail = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to($userEmail)
            ->subject('Someone comments your decision')
            ->html($this->renderView('email/emailForComments.html.twig', [
                'userEmail' => $userEmail,
                'userFirstName' => $firstName,
                'userLastName' => $lastName,
                'linkDecision' => $slug,
            ]));
        $this->mailer->send($mail);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function emailWhenSomeoneGaveHimNotice(
        string $userEmail,
        string $firstName,
        string $lastName,
        string $slug
    ): void {
        $mail = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to($userEmail)
            ->subject('Someone gave him notice on your decision')
            ->html($this->renderView('email/emailForNotice.html.twig', [
                'userEmail' => $userEmail,
                'userFirstName' => $firstName,
                'userLastName' => $lastName,
                'linkDecision' => $slug,
            ]));
        $this->mailer->send($mail);
    }

    public function emailDecisionStatusSetLate(
        string $userEmail,
        string $firstName,
        string $lastName,
        string $slug
    ): void {
        $mail = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to($userEmail)
            ->subject('Your decision is late in its process')
            ->html($this->renderView('email/emailDelayDecision.html.twig', [
                'userEmail' => $userEmail,
                'userFirstName' => $firstName,
                'userLastName' => $lastName,
                'linkDecision' => $slug,
            ]));
        $this->mailer->send($mail);
    }
}
