<?php

namespace App\Controller;

use App\Form\SearchDecisionsType;
use App\Repository\DecisionRepository;
use App\Repository\NationalityRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HubController extends AbstractController
{
    #[Route('/hub/{lang}', name: 'app_hub_lang')]
    public function decisionByFrenchHub(
        DecisionRepository $decisionRepository,
        UserRepository $userRepository,
        StatusRepository $statusRepository,
        NationalityRepository $nationality,
        Request $request,
        string $lang
    ): Response {
        // Get nationality by name
        $french = $nationality->findBy(['name' => 'french']);
        $english = $nationality->findBy(['name' => 'english']);
        $espagnol = $nationality->findBy(['name' => 'espagnol']);

        // Get users by nationality
        $frenchUsers = $userRepository->findBy(['nationality' => $french]);
        $englishUsers = $userRepository->findBy(['nationality' => $english]);
        $espagnolUsers = $userRepository->findBy(['nationality' => $espagnol]);

        // Get status one by one
        $statusStarted = $statusRepository->findOneBy(['name' => 'Decision started']);
        $statusFirstDecision = $statusRepository->findOneBy(['name' => 'First decision']);
        $statusConflict = $statusRepository->findOneBy(['name' => 'Decision in conflict']);
        $statusDefinitive = $statusRepository->findOneBy(['name' => 'Final decision']);
        $statusCanceled = $statusRepository->findOneBy(['name' => 'Decision cancelled']);
        $statusFinished = $statusRepository->findOneBy(['name' => 'Decision taken']);

        $decisionsStarted = [];
        $firstDecisions = [];
        $decisionsInConflict = [];
        $decisionsDefinitive = [];
        $decisionsCanceled = [];
        $decisionsFinished = [];

        if ($lang == 'fr') {
            //Get decision by status and by french user
            $decisionsStarted = $decisionRepository->findBy(['status' => $statusStarted, 'author' => $frenchUsers]);
            $firstDecisions = $decisionRepository->findBy(['status' => $statusFirstDecision, 'author' => $frenchUsers]);
            $decisionsInConflict = $decisionRepository->findBy(['status' => $statusConflict, 'author' => $frenchUsers]);
            $decisionsDefinitive = $decisionRepository->findBy([
                'status' => $statusDefinitive,
                'author' => $frenchUsers
            ]);
            $decisionsCanceled = $decisionRepository->findBy(['status' => $statusCanceled, 'author' => $frenchUsers]);
            $decisionsFinished = $decisionRepository->findBy(['status' => $statusFinished, 'author' => $frenchUsers]);
        } elseif ($lang == 'en') {
            //Get decision by status and by english user
            $decisionsStarted = $decisionRepository->findBy(['status' => $statusStarted, 'author' => $englishUsers]);
            $firstDecisions = $decisionRepository->findBy([
                'status' => $statusFirstDecision,
                'author' => $englishUsers
            ]);
            $decisionsInConflict = $decisionRepository->findBy([
                'status' => $statusConflict,
                'author' => $englishUsers
            ]);
            $decisionsDefinitive = $decisionRepository->findBy([
                'status' => $statusDefinitive,
                'author' => $englishUsers
            ]);
            $decisionsCanceled = $decisionRepository->findBy(['status' => $statusCanceled, 'author' => $englishUsers]);
            $decisionsFinished = $decisionRepository->findBy(['status' => $statusFinished, 'author' => $englishUsers]);
        } elseif ($lang == 'es') {
            //Get decision by status and by espagnol user
            $decisionsStarted = $decisionRepository->findBy(['status' => $statusStarted, 'author' => $espagnolUsers]);
            $firstDecisions = $decisionRepository->findBy([
                'status' => $statusFirstDecision,
                'author' => $espagnolUsers
            ]);
            $decisionsInConflict = $decisionRepository->findBy([
                'status' => $statusConflict,
                'author' => $espagnolUsers
            ]);
            $decisionsDefinitive = $decisionRepository->findBy([
                'status' => $statusDefinitive,
                'author' => $espagnolUsers
            ]);
            $decisionsCanceled = $decisionRepository->findBy(['status' => $statusCanceled, 'author' => $espagnolUsers]);
            $decisionsFinished = $decisionRepository->findBy(['status' => $statusFinished, 'author' => $espagnolUsers]);
        }

        $form = $this->createForm(SearchDecisionsType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->searchDecisionHub($decisionRepository, $form, $lang);
        }

        return $this->render('hub/decisionByHub.html.twig', [
            'decisionsStarted' => $decisionsStarted,
            'firstDecisionsTaken' => $firstDecisions,
            'decisionsInConflict' => $decisionsInConflict,
            'decisionsDefinitive' => $decisionsDefinitive,
            'decisionsCanceled' => $decisionsCanceled,
            'decisionsFinished' => $decisionsFinished,
            'form' => $form->createView(),
        ]);
    }

    public function searchDecisionHub(
        DecisionRepository $decisionRepository,
        FormInterface $form,
        string $lang
    ): Response {
        $decisions = [];
        if ($lang === 'fr') {
            $lang = 'french';
        };
        if ($lang === 'en') {
            $lang = 'english';
        };
        if ($lang === 'es') {
            $lang = 'espagnol';
        };
        $decisionsResult = $decisionRepository->findByDecisionTitle($form->getData()['search']);
        foreach ($decisionsResult as $decisionResult) {
            if ($decisionResult->getAuthor()->getNationality()->getName() === $lang) {
                $decisions[] = $decisionResult;
            }
        }
        return $this->render('home/decisionsSearched.html.twig', [
            'decisions' => $decisions,
            'form' => $form->createView()
        ]);
    }
}
