<?php

namespace App\Controller;

use App\Service\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TableController extends AbstractController
{
    public function __construct(
        private readonly TableService $mainService,
    )
    {
    }

    #[Route('/tableA', name: 'app_table_a')]
    public function showTableA(Request $request): Response
    {
        $dataTable = $this->mainService->getDataFromTableA();
        $pagination = $this->mainService->getPagination($dataTable, $request->query->getInt('page', 1));

        return $this->render('table/showTableA.html.twig', [
            'pagination' => $pagination,
            'action' => 'Table A',
        ]);
    }

    #[Route('/tableB', name: 'app_table_b')]
    public function showTableB(Request $request): Response
    {
        $dataTable = $this->mainService->getDataFromTableB();
        $pagination = $this->mainService->getPagination($dataTable, $request->query->getInt('page', 1));

        return $this->render('table/showTableB.html.twig', [
            'pagination' => $pagination,
            'action' => 'Table B',
        ]);
    }

    #[Route('/clear_cache', name: 'app_clear_cache')]
    public function clearCache(Request $request): Response
    {
        $routeParams = $request->query->all('routeParams');
        foreach ($routeParams as $params) {
            $this->mainService->clearCache($params);
        }
        $this->addFlash('success', 'Table cache cleared successfully');

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer ?: $this->generateUrl('homepage'));
    }
}
