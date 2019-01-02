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
    public function index(StagesGenerator $stagesGenerator)
    {
		$stages = $stagesGenerator->getAll();

        return $this->render('home_page/index.html.twig', [
            'stages'   =>  $stages,
        ]);
    }
}
