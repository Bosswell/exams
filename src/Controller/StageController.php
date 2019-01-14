<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Stage;

class StageController extends AbstractController
{
    /**
     * @Route("/egzaminy/{id}-{friendly_url}", name="stage", requirements={"id": "\d+"})
     */
    public function index(Request $request, Stage $stage, $friendly_url)
    {
        $qualifications = $stage->getQualifications();
        $request->getSession()->set('current_stage_id', $stage->getId());
        $request->getSession()->set('friendly_stage_url', $friendly_url);
        
        return $this->render('stage/index.html.twig', [
            'qualifications' =>  $qualifications,
            'stage' =>  $stage
        ]);
    }
}
