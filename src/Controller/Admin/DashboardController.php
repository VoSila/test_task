<?php

namespace App\Controller\Admin;

use App\Entity\TableA;
use App\Entity\TableB;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Test task');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Clear cache table A', 'fa fa-trash-o', 'app_clear_cache', [
           'key' => 'data_from_table_a'
        ]);
        yield MenuItem::linkToRoute('Clear cache table B', 'fa fa-trash-o', 'app_clear_cache', [
            'key' => 'data_from_table_b'
        ]);
        yield MenuItem::linkToRoute('Home', 'fa fa-sign-out', 'app_main');

        yield MenuItem::section('Tables');
        yield MenuItem::linkToCrud('TableA', 'fas fa-list', TableA::class);
        yield MenuItem::linkToCrud('TableB', 'fas fa-list', TableB::class);

    }
}
