<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\StagesGenerator;
use App\Helper\Pagination;


class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index(StagesGenerator $stagesGenerator, Request $request)
    {
        $totalStagesCount = $stagesGenerator->getTotalCount();

        if (empty($request->get('page')) || !ctype_digit($request->get('page')))
            $currentPage = 1;
        else 
            $currentPage = $request->get('page');

        $pagination = new Pagination($currentPage, $totalStagesCount, 3, 3);
        $stages = $stagesGenerator->getRange($pagination->getLimitOffset(), 3);
        

        return $this->render('home_page/index.html.twig', [
            'stages'              =>  $stages,
            'allPages'            =>  $pagination->getPagesQuantity(),
            'currentPage'         =>  $pagination->getCurrentPage(),
            'totalStagesCount'    =>  $totalStagesCount,
            'range'               =>  $pagination->getRange()
        ]);
    }
}
