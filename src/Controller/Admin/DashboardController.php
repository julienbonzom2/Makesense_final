<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Decision;
use App\Entity\Nationality;
use App\Entity\User;
use App\Repository\DecisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setRoute('statistics_admin')
            ->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Make Sense Dashboard')
            ->disableDarkMode();
    }

    public function configureAssets(): Assets
    {
        $assets = parent::configureAssets();

        $assets->addWebpackEncoreEntry('app');

        return $assets;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Home', 'fas fa-home', 'app_home');

        yield MenuItem::section('Users');
        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add user', 'fas fa-plus', User::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show all users', 'fas fa-user', User::class),
            MenuItem::linkToCrud('Add nationality', 'fas fa-plus', Nationality::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show nationalities', 'fas fa-flag', Nationality::class)
        ]);


        yield MenuItem::section('Decisions');
        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Decision', 'fas fa-file', Decision::class)
                ->setDefaultSort(['createdAt' => 'ASC']),
            MenuItem::linkToCrud('Decision with a delay in their process', 'fa-solid fa-clock', Decision::class)
                ->setAction('lateStatusUpdate'),
            MenuItem::linkToCrud('Stats', 'fa fa-chart-simple', Decision::class)
                ->setAction('displayStats'),

            MenuItem::linkToCrud('Add category', 'fas fa-plus', Category::class)
                ->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show category', 'fas fa-flag', Category::class),

        ]);
    }
}
