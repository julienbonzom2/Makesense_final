<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DecisionRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserDecisionsController extends AbstractController
{
    #[Route('/mydecisions', name: 'app_user_decisions')]
    public function index(DecisionRepository $decisionRepository, StatusRepository $statusRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $allDecisions = $user->getDecisions();
        $decisionsOnGoing = $user->getOnGoingDecisions();
        $decisions = [];
        $statusLate = $statusRepository->findBy(['name' => 'Late decision']);
        $decisionsLate = $decisionRepository->findBy(['author' => $user->getId(), 'status' => $statusLate]);

        foreach ($allDecisions as $allDecision) {
            if ($allDecision->getStatus()->getName() != 'Late decision') {
                $decisions[] = $allDecision;
            }
        }

        return $this->renderForm('user_decisions/index.html.twig', [
            'decisions' => $decisions,
            'lateDecisions' => $decisionsLate,
            'draftDecisions' => $decisionsOnGoing,
        ]);
    }
}
