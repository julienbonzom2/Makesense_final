<?php

namespace App\Controller;

use App\Entity\Associated;
use App\Entity\Decision;
use App\Entity\OngoingAssociated;
use App\Entity\OngoingDecision;
use App\Entity\User;
use App\Form\AvatarType;
use App\Form\CreateDecisionStep1FormType;
use App\Form\CreateDecisionStep2FormType;
use App\Form\CreateDecisionStep3FormType;
use App\Form\CreateDecisionStep4FormType;
use App\Form\CreateDecisionStep6FormType;
use App\Repository\OngoingAssociatedRepository;
use App\Repository\OngoingDecisionRepository;
use App\Repository\StatusRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/create/', name: 'app_create_decision_')]
class CreateDecisionController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('create_decision/index.html.twig', [
            'controller_name' => 'CreateDecisionController',
        ]);
    }

    #[Route('step1/{id<\d+>?}', name: 'step1')]
    public function createDecisionStep1(
        Request $request,
        EntityManagerInterface $manager,
        ?OngoingDecision $decision,
    ): Response {
        if (!isset($decision)) {
            $decision = new OngoingDecision();
        }
        $form = $this->createForm(CreateDecisionStep1FormType::class, $decision);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $decision->setOngoingAuthor($user);
            if (is_null($decision->getCurrentStep())) {
                $decision->setCurrentStep('1');
            };
            $manager->persist($decision);
            $manager->flush();
            if ($decision->getCurrentStep() === '5') {
                return $this->redirectToRoute('app_create_decision_step6', [
                    'id' => $decision->getId(),
                ]);
            }

            return $this->redirectToRoute('app_create_decision_step2', [
                'id' => $decision->getId(),
            ]);
        }
        return $this->render('create_decision/createDecision.html.twig', [
            'form' => $form->createView(),
            'decision' => $decision,
            'step' => '1'

        ]);
    }

    #[Route('step2/{id}', name: 'step2')]
    public function createDecisionStep2(
        Request $request,
        EntityManagerInterface $manager,
        OngoingDecisionRepository $repository,
        int $id,
    ): Response {
        $decision = $repository->find($id);
        $form = $this->createForm(CreateDecisionStep2FormType::class, $decision);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($decision->getCurrentStep() === '1') {
                $decision->setCurrentStep('2');
            };
            $manager->persist($decision);
            $manager->flush();
            if ($decision->getCurrentStep() === '5') {
                return $this->redirectToRoute('app_create_decision_step6', [
                    'id' => $decision->getId(),
                ]);
            }
            return $this->redirectToRoute('app_create_decision_step3', [
                'id' => $id
            ]);
        }

        return $this->render('create_decision/createDecisionStep2.html.twig', [
            'form' => $form->createView(),
            'decision' => $decision,
            'step' => '2'
        ]);
    }

    #[Route('step3/{id}', name: 'step3')]
    public function createDecisionStep3(
        Request $request,
        EntityManagerInterface $manager,
        OngoingDecisionRepository $repository,
        int $id
    ): Response {
        $decision = $repository->find($id);
        $form = $this->createForm(CreateDecisionStep3FormType::class, $decision);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($decision->getCurrentStep() === '2') {
                $decision->setCurrentStep('3');
            };
            $manager->persist($decision);
            $manager->flush();
            if ($decision->getCurrentStep() === '5') {
                return $this->redirectToRoute('app_create_decision_step6', [
                    'id' => $decision->getId(),
                ]);
            }
            return $this->redirectToRoute('app_create_decision_step4', [
                'id' => $id
            ]);
        }

        return $this->render('create_decision/createDecisionStep3.html.twig', [
            'form' => $form->createView(),
            'decision' => $decision,
            'step' => '3'
        ]);
    }

    #[Route('step4/{id}', name: 'step4')]
    public function createDecisionStep4(
        Request $request,
        EntityManagerInterface $manager,
        OngoingDecisionRepository $repository,
        int $id
    ): Response {
        $decision = $repository->find($id);
        $form = $this->createForm(CreateDecisionStep4FormType::class, null, [
            'decision' => $decision
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $electedUsers = $form->getData()['user']->toArray();
            $selectedSearchArray = $form->getData()['search']->toArray();
            foreach ($selectedSearchArray as $selectedFromSearch) {
                $electedUsers[] = $selectedFromSearch;
            }
            foreach ($electedUsers as $user) {
                $associated = new OngoingAssociated();
                $associated->setOngoingDecision($repository->find($id));
                $associated->setUser($user);
                $associated->setOngoingStatus('Impacted');
                $decision->addOngoingAssociated($associated);
                $manager->persist($associated);
            }
            if ($decision->getCurrentStep() === '3') {
                $decision->setCurrentStep('4');
            };
            $manager->persist($decision);
            $manager->flush();
            if ($decision->getCurrentStep() === '5') {
                return $this->redirectToRoute('app_create_decision_step6', [
                    'id' => $decision->getId(),
                ]);
            }
            return $this->redirectToRoute('app_create_decision_step5', [
                'id' => $id
            ]);
        }

        return $this->render('create_decision/createDecisionStep4.html.twig', [
            'form' => $form->createView(),
            'decision' => $decision,
            'step' => '4'
        ]);
    }

    #[Route('step5/{id}', name: 'step5')]
    public function createDecisionStep5(
        Request $request,
        EntityManagerInterface $manager,
        OngoingDecisionRepository $repository,
        int $id
    ): Response {
        $decision = $repository->find($id);
        $form = $this->createForm(CreateDecisionStep4FormType::class, null, [
            'decision' => $decision
        ]);
        $form->handleRequest($request);
        $impacteds = [];
        foreach ($decision->getOngoingAssociateds()->toArray() as $impactedPeople) {
            $impacteds[] = $impactedPeople->getUser();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $electedUsers = $form->getData()['user']->toArray();
            $selectedSearchArray = $form->getData()['search']->toArray();
            foreach ($selectedSearchArray as $selectedFromSearch) {
                $electedUsers[] = $selectedFromSearch;
            }
            foreach (array_unique($electedUsers) as $user) {
                $associated = new OngoingAssociated();
                $associated->setOngoingDecision($repository->find($id));
                $associated->setUser($user);
                $associated->setOngoingStatus('Expert');
                $decision->addOngoingAssociated($associated);
                $manager->persist($associated);
            }
            if ($decision->getCurrentStep() === '4') {
                $decision->setCurrentStep('5');
            };
            $manager->persist($decision);
            $manager->flush();
            return $this->redirectToRoute('app_create_decision_step6', [
                'id' => $id
            ]);
        }

        return $this->render('create_decision/createDecisionStep5.html.twig', [
            'form' => $form->createView(),
            'decision' => $decision,
            'impacteds' => $impacteds,
            'step' => '5'
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('step6/{id}', name: 'step6')]
    public function createDecisionFinalStep(
        Request $request,
        EntityManagerInterface $manager,
        OngoingDecisionRepository $ongoingRepository,
        OngoingAssociatedRepository $associatedRepository,
        StatusRepository $statusRepository,
        int $id,
        EmailSender $emailSender,
        SluggerInterface $slugger
    ): Response {
        $decisionInCreation = $ongoingRepository->find($id);
        $draftImpactedArray = [];
        $draftExpertArray = [];
        foreach ($decisionInCreation->getOngoingAssociateds() as $associated) {
            if ($associated->getOngoingStatus() === 'Impacted') {
                $draftImpactedArray[] = $associated->getUser();
            } elseif ($associated->getOngoingStatus() === 'Expert') {
                $draftExpertArray[] = $associated->getUser();
            }
        };
        $form = $this->createForm(CreateDecisionStep6FormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $decision = new Decision();
            /** @var User $user */
            $user = $this->getUser();
            $decision->setAuthor($user);
            $decision->setTitle($decisionInCreation->getOngoingTitle());
            $slug = $slugger->slug($decision->getTitle());
            $decision->setSlug($slug);
            $decision->setProblem($decisionInCreation->getOngoingProblem());
            $decision->setDescription($decisionInCreation->getOngoingDescription());
            $decision->setImpactRate($decisionInCreation->getOngoingImpactRate());
            $decision->setEffortRate($decisionInCreation->getOngoingEffortRate());
            $decision->setCategory($decisionInCreation->getCategory());
            $decision->setProfit($decisionInCreation->getOngoingProfit());
            $decision->setDrawback($decisionInCreation->getOngoingDrawback());
            $decision->setStatus($statusRepository->findOneBy(['name' => 'Decision started']));
            $manager->persist($decision);
            foreach ($decisionInCreation->getOngoingAssociateds() as $ongoingAssociated) {
                $associated = new Associated();
                $associated->setStatus($ongoingAssociated->getOngoingStatus());
                $associated->setDecision($decision);
                $associated->setUser($ongoingAssociated->getUser());
                $associated->setIsChecked(false);
                $manager->persist($associated);
                $associatedRepository->remove($ongoingAssociated);
            }
            $ongoingRepository->remove($decisionInCreation);
            $this->addFlash('success', 'Decisions created successfully');
            $manager->flush();
            return $this->redirectToRoute('app_user_decisions');
        }
        return $this->render('create_decision/createDecisionStep6.html.twig', [
            'form' => $form->createView(),
            'decision' => $decisionInCreation,
            'impacteds' => $draftImpactedArray,
            'experts' => $draftExpertArray,
            'step' => '6'
        ]);
    }
}
