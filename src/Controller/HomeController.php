<?php

namespace App\Controller;

use App\Form\SearchDecisionsType;
use App\Repository\DecisionRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/', name: 'app_home')]
    public function index(
        DecisionRepository $decisionRepository,
        StatusRepository $statusRepository,
        Request $request,
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        // Get status one by one
        $statusStarted = $statusRepository->findOneBy(['name' => 'Decision started']);
        $statusFirstDecision = $statusRepository->findOneBy(['name' => 'First decision']);
        $statusConflict = $statusRepository->findOneBy(['name' => 'Decision in conflict']);
        $statusDefinitive = $statusRepository->findOneBy(['name' => 'Final decision']);
        $statusCanceled = $statusRepository->findOneBy(['name' => 'Decision cancelled']);
        $statusFinished = $statusRepository->findOneBy(['name' => 'Decision taken']);
        $statusLate = $statusRepository->findOneBy(['name' => 'Late decision']);

        $form = $this->createForm(SearchDecisionsType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $decisions = $decisionRepository->findByDecisionTitle($search);
            return $this->renderForm('home/decisionsSearched.html.twig', [
                'decisions' => $decisions,
                'form' => $form,
            ]);
        } else {
            $decisionsStarted = $decisionRepository->findBy(['status' => $statusStarted]);
            $firstDecisions = $decisionRepository->findBy(['status' => $statusFirstDecision]);
            $decisionsInConflict = $decisionRepository->findBy(['status' => $statusConflict]);
            $decisionsDefinitive = $decisionRepository->findBy(['status' => $statusDefinitive]);
            $decisionsCanceled = $decisionRepository->findBy(['status' => $statusCanceled]);
            $decisionsFinished = $decisionRepository->findBy(['status' => $statusFinished]);
            $decisionsLate = $decisionRepository->findBy(['status' => $statusLate]);
        }

        return $this->renderForm('home/index.html.twig', [
            'decisionsStarted' => $decisionsStarted,
            'firstDecisionsTaken' => $firstDecisions,
            'decisionsInConflict' => $decisionsInConflict,
            'decisionsDefinitive' => $decisionsDefinitive,
            'decisionsCanceled' => $decisionsCanceled,
            'decisionsFinished' => $decisionsFinished,
            'decisionsLate' => $decisionsLate,
            'form' => $form
        ]);
    }
}
