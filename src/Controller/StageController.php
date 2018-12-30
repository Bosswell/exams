<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class StageController extends AbstractController
{
    /**
     * @Route("/egzaminy/{id}", name="stage", requirements={"id": "\d+"})
     */
    public function index(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $qualifications = $em->getRepository('App:Qualification')->findByStageId($id);

        $request->getSession()->set('current_stage', $id);
        
        return $this->render('stage/index.html.twig', [
            'qualifications' =>  isset($qualifications) ? $qualifications : null
        ]);
    }
}
