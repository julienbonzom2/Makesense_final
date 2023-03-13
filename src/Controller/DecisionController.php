<?php

namespace App\Controller;

use App\Entity\Associated;
use App\Entity\Comments;
use App\Entity\Decision;
use App\Entity\Status;
use App\Entity\User;
use App\Form\CommentsType;
use App\Form\PostCommentFormType;
use App\Form\PostOpinionFormType;
use App\Form\UpdateDecisionFormType;
use App\Repository\AssociatedRepository;
use App\Repository\CommentsRepository;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/decision')]
class DecisionController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/{slug}', name: 'decision_show')]
    public function showOneDecision(
        CommentsRepository $commentsRepository,
        UserRepository $userRepository,
        Decision $decision,
        EntityManagerInterface $manager,
        Request $request,
        EmailSender $emailSender,
    ): Response {
        $formComment = $this->createForm(PostCommentFormType::class);
        $formComment->handleRequest($request);
        $updateDecision = $this->createForm(UpdateDecisionFormType::class);
        $updateDecision->handleRequest($request);
        $formOpinion = $this->createForm(PostOpinionFormType::class);
        $formOpinion->handleRequest($request);
        $status = null;
        $user = null;
        $comments = $commentsRepository->findCommentsBeforeFirstDecision($decision);
        $opinions = $commentsRepository->findOpinionsBeforeFirstDecision($decision);
        $commentFirstDecision = $commentsRepository->findCommentsOnFirstDecision($decision);
        $opinionFirstDecision = $commentsRepository->findOpinionsOnFirstDecision($decision);
        $commentFinalDecision = $commentsRepository->findCommentsOnFinalDecision($decision);
        $opinionFinalDecision = $commentsRepository->findOpinionsOnFinalDecision($decision);
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_home');
        } elseif ($this->getUser() !== null) {
            /** @var User $user */
            $user = $this->getUser();
        }
        $associatedUser = $manager->getRepository(Associated::class)->findOneBy([
            'user' => $user->getId(),
            'decision' => $decision->getId()
        ]);
        if ($associatedUser) {
            $status = $associatedUser->getStatus();
        }
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            return $this->postComment($formComment, $decision, $associatedUser, $emailSender, $manager);
        }
        if ($formOpinion->isSubmitted() && $formOpinion->isValid()) {
            return $this->postOpinion($formOpinion, $decision, $associatedUser, $emailSender, $manager);
        }
        if ($updateDecision->isSubmitted()) {
            $updateDecision = $updateDecision->getData()['updateDecision'];
            return $this->updateDecision($decision, $updateDecision, $manager);
        }

        return $this->renderForm('decision/showDecision.html.twig', [
            'decision' => $decision,
            'status' => $status,
            'formComment' => $formComment,
            'formOpinion' => $formOpinion,
            'opinionOnStartedDecision' => $opinions,
            'commentsOnStartedDecision' => $comments,
            'formFirstDecision' => $updateDecision,
            'commentsOnFirstDecision' => $commentFirstDecision,
            'opinionOnFirstDecision' => $opinionFirstDecision,
            'commentsOnFinalDecision' => $commentFinalDecision,
            'opinionOnFinalDecision' => $opinionFinalDecision
        ]);
    }

    #[
        Route('/notif/{id}', name: 'bool_to_true')]
    public function putBooleanToTrue(
        Decision $decision,
        AssociatedRepository $associatedRepository,
        EntityManagerInterface $manager,
    ): Response {
        $decision->getAssociateds();
        $associate = $associatedRepository->findBy(['decision' => $decision]);
        $user = $this->getUser();
        foreach ($associate as $associat) {
            if ($associat->getUser() === $user) {
                $associat->setIsChecked(true);
                $manager->persist($associat);
            }
            $manager->flush();
        }
        return $this->redirectToRoute('decision_show', [
            'slug' => $decision->getSlug(),
        ]);
    }

    public function updateDecision(
        Decision $decision,
        string $updateDecision,
        EntityManagerInterface $manager
    ): Response {
        $statusRepository = $manager->getRepository(Status::class);
        if ($decision->getStatus() == $statusRepository->findOneBy(['name' => 'Decision started'])) {
            $decision->setFirstDecision($updateDecision);
            $decision->setStatus($statusRepository->findOneBy(['name' => 'First decision']));
            $decision->setFirstDecisionAt(new DateTimeImmutable());
        } elseif ($decision->getStatus() == $statusRepository->findOneBy(['name' => 'First decision'])) {
            $decision->setFinalDecision($updateDecision);
            $decision->setStatus($statusRepository->findOneBy(['name' => 'Final decision']));
            $decision->setFinalDecisionAt(new DateTimeImmutable());
        }
        $manager->persist($decision);
        $manager->flush();
        return $this->redirectToRoute('decision_show', [
            'slug' => $decision->getSlug()
        ]);
    }

    public function postComment(
        FormInterface $formComment,
        Decision $decision,
        Associated $associatedUser,
        EmailSender $emailSender,
        EntityManagerInterface $manager
    ): Response {
        $comment = new Comments();
        $comment->setContent($formComment->getData()['content']);
        $comment->setDecision($decision);
        $comment->setCreatedAt(new DateTimeImmutable());
        $comment->setAssociated($associatedUser);
        $comment->setCommentType('Comment');

        $emailSender->emailWhenSomeoneComments(
            $decision->getAuthor()->getEmail(),
            $decision->getAuthor()->getFirstname(),
            $decision->getAuthor()->getLastname(),
            $decision->getSlug()
        );
        $manager->persist($comment);
        $manager->flush();
        return $this->redirectToRoute('decision_show', [
            'slug' => $decision->getSlug()
        ]);
    }

    public function postOpinion(
        FormInterface $formOpinion,
        Decision $decision,
        Associated $associatedUser,
        EmailSender $emailSender,
        EntityManagerInterface $manager
    ): Response {
        $comment = new Comments();
        $comment->setContent($formOpinion->getData()['content']);
        $comment->setDecision($decision);
        $comment->setCreatedAt(new DateTimeImmutable());
        $comment->setAssociated($associatedUser);
        $comment->setCommentType('Opinion');
        $manager->persist($comment);
        $associatedUser->setIsFavorable($formOpinion->getData()['isFavorable']);
        $emailSender->emailWhenSomeoneGaveHimNotice(
            $decision->getAuthor()->getEmail(),
            $decision->getAuthor()->getFirstname(),
            $decision->getAuthor()->getLastname(),
            $decision->getSlug()
        );
        if ($decision->getStatus()->getName() === 'First decision' && !$formOpinion->getData()['isFavorable']) {
            $decision->setStatus($manager->getRepository(Status::class)->findOneBy([
                'name' => 'Decision in conflict'
            ]));
            $manager->persist($decision);
        }
        $manager->flush();
        return $this->redirectToRoute('decision_show', [
            'slug' => $decision->getSlug()
        ]);
    }
}
