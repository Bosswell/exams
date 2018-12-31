<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Stage;

class StageController extends AbstractController
{
    /**
     * @Route("/egzaminy/{id}", name="stage", requirements={"id": "\d+"})
     */
    public function index(Request $request, Stage $stage)
    {
        $qualifications = $stage->getQualifications();
        $request->getSession()->set('current_stage_id', $stage->getId());
        
        return $this->render('stage/index.html.twig', [
            'qualifications' =>  $qualifications,
            'stage' =>  $stage
        ]);
    }
}
