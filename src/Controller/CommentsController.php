<?php

namespace App\Controller;

use App\Entity\Associated;
use App\Entity\Comments;
use App\Entity\Decision;
use App\Form\Comments1Type;
use App\Form\UpdateCommentFormType;
use App\Repository\CommentsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comments')]
class CommentsController extends AbstractController
{
    #[Route('/', name: 'app_comments_index', methods: ['GET'])]
    public function index(CommentsRepository $commentsRepository): Response
    {
        return $this->render('comments/index.html.twig', [
            'comments' => $commentsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comments_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentsRepository $commentsRepository): Response
    {
        $comment = new Comments();
        $form = $this->createForm(UpdateCommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentsRepository->save($comment, true);
            return $this->redirectToRoute('app_comments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comments/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comments_show', methods: ['GET'])]
    public function show(Comments $comment): Response
    {
        return $this->render('comments/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comments $comment, EntityManagerInterface $manager): Response
    {
        $comment->setContent($_POST['comment']);
        $comment->setCreatedAt(new DateTimeImmutable());
        $author = $comment->getAssociated();
        if ($comment->getCommentType() === 'Opinion') {
            $vote = $_POST['vote'];
            if ($vote === 'true') {
                $author->setIsFavorable(true);
            } elseif ($vote === 'false') {
                $author->setIsFavorable(false);
            }
            $manager->persist($author);
        }
        $manager->persist($comment);
        $manager->flush();
        return $this->redirectToRoute('decision_show', [
            'slug' => $comment->getDecision()->getSlug()
        ]);
    }

    #[Route('/{id}', name: 'app_comments_delete', methods: ['POST'])]
    public function delete(Request $request, Comments $comment, CommentsRepository $commentsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $commentsRepository->remove($comment, true);
        }
        return $this->redirectToRoute('decision_show', [
            'slug' => $comment->getDecision()->getSlug()
        ]);
    }
}
