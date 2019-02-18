<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Stage;
use App\Service\StagesGenerator;

class StageController extends AbstractController
{
    /**
     * @Route("/{friendly_url}/wybierz-kwalifikacje/{id}", name="stage", requirements={"id": "\d+"})
     */
    public function index(Request $request, Stage $stage, $friendly_url)
    {
        $qualifications = $stage->getQualifications();
        
        return $this->render('stage/index.html.twig', [
            'qualifications' =>  $qualifications,
            'stage' =>  $stage
        ]);
    }
}
