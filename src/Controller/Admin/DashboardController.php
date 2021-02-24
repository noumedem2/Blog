<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration')
            ->setTextDirection('ltr');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Home', 'fa fa-home');

        yield  MenuItem::section('Blog');
        yield  MenuItem::linkToCrud('Articles', 'fa fa-file-text', Post::class)
            ->setController(PostCrudController::class);
        yield  MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class)
            ->setController(CategoryCrudController::class)
            ->setController(CategoryCrudController::class);
    }
}
