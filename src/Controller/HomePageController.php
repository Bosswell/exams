<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\StagesGenerator;


class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index(StagesGenerator $stagesGenerator, Request $request)
    {
        $limit = 5;

        if (!empty($request->get('page'))) {
            $page = $request->get('page');

        } else {
            $page = 1;
        }
        $from = $page * $limit - $limit;
        $to = $from + $limit;

        $stagesGenerator->generate();
        $totalStagesCount = $stagesGenerator->getTotalCount();
        $stages = $stagesGenerator->getRange($from, $to);

        return $this->render('home_page/index.html.twig', [
            'stages'        =>  $stages,
            'pages'         =>  ceil($totalStagesCount / $limit),
            'current_page'  =>  $page
        ]);
    }
}
