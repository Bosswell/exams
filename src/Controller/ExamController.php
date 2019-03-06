<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Exam\Exam;
use App\Exam\ExamValidator;
use App\Entity\Question;
use App\Entity\Qualification;
use App\Service\QualificationGenerator;

class ExamController extends AbstractController
{
    /**
     * @Route("/egzamin-zawodowy/generator/{stage_id}-{friendly_stage_url}", name="redirect_exam")
     */
    public function selectQualificationsFormRedirection(Request $request, QualificationGenerator $qualificationGenerator, $friendly_stage_url, $stage_id)
    {
        $qualification_id = $request->get('qualification_id');
        $question_quantity = $request->get('question_quantity');

        if (empty($qualification_id) || empty($question_quantity)) {
            $this->redirectToRoute('homepage', null, 302);
        }
        
        $friendly_qualification_url = $qualificationGenerator->get($qualification_id)->getFriendlyUrl();;

        return $this->redirectToRoute('generate_exam', [
            'qualification_id'   =>  $qualification_id,
            'question_quantity'  =>  $question_quantity,
            'friendly_stage_url' =>  $friendly_stage_url,
            'friendly_qualification_url' => $friendly_qualification_url,
            'stage_id'  =>  $stage_id
        ], 302);
    }

    /**
     * @Route("/egzamin-zawodowy/{question_quantity}/{stage_id}/{qualification_id}/{friendly_stage_url}/{friendly_qualification_url}", name="generate_exam")
     */
    public function generateExam(Request $request, $qualification_id, $stage_id, $question_quantity, $friendly_qualification_url, $friendly_stage_url)
    {
        if (empty($qualification_id) || empty($question_quantity)) {
            return $this->redirectToRoute('home_page', [], 302);
        }

        if (!ExamValidator::isCorrectNumberOfQuestions($question_quantity)) {
            return $this->redirectToRoute('home_page', [], 302);
        }
        
        $em = $this->getDoctrine()->getManager();
        $qualification = $em->getRepository(Qualification::class)->find($qualification_id);
        $questions = $em->getRepository(Question::class)
                        ->getRandomEntities($qualification_id, $question_quantity);
                    
        $exam = new Exam($questions);

        if (empty($questions)) {
            throw new NotFoundHttpException("Questions has been not found");
        }

        $request->getSession()->set('exam', $exam);
        $request->getSession()->set('qualification_id', $qualification_id);
        $request->getSession()->set('qualification_designation', $qualification->getDesignation());
        $request->getSession()->set('qualification_meta_desc', $qualification->getMetaDescription());
        $request->getSession()->set('friendly_stage_url', $friendly_stage_url);
        $request->getSession()->set('friendly_qualification_url', $qualification->getFriendlyUrl());
        $stage_name = ucfirst(str_replace('-', ' ', $friendly_stage_url));
        $request->getSession()->set('stage_name', $stage_name);
        $request->getSession()->set('stage_id', $stage_id);

        return $this->render('exam/show.html.twig', [
            'questions'  => $questions,
            'qualification'    =>  $qualification,
            'question_quantity'    =>  $question_quantity,
            'stage_name'    =>  $stage_name,
            'stage_id' => $stage_id,
            'friendly_stage_url' => $friendly_stage_url
        ]);
    }

    /**
     * @Route("/egzamin-zawodowy/{friendly_stage}/podsumowanie", name="check_exam")
     */
    public function checkExam(Request $request)
    {
        $exam = $request->getSession()->get('exam');

        if (empty($exam)) {
            return $this->redirectToRoute('home_page');
        }

        $stage_id = $request->getSession()->get('stage_id');
        $friendly_qualification_url = $request->getSession()->get('friendly_qualification_url');
        $friendly_stage_url = $request->getSession()->get('friendly_stage_url');
        $qualification_id = $request->getSession()->get('qualification_id');
        $stage_name = $request->getSession()->get('stage_name');
        $qualification_designation = $request->getSession()->get('qualification_designation');
        $qualification_meta_desc = $request->getSession()->get('qualification_meta_desc');

        $answers = $request->request->all();
        $exam->setAnswers($answers['answers'])
            ->checkQuestions();

        $request->getSession()->clear();
        
        return $this->render('exam/summary.html.twig', [
            'questions'         => $exam->getQuestions(),
            'points'            => $exam->getPoints(),
            'percent'           => $exam->getPercent(),
            'question_quantity' => $exam->getQuestionQuantity(),
            'stage_id'  => $stage_id,
            'friendly_qualification_url' => $friendly_qualification_url,
            'friendly_stage_url' => $friendly_stage_url,
            'qualification_id' => $qualification_id,
            'stage_name' => $stage_name,
            'qualification_designation' =>  $qualification_designation,
            'qualification_meta_desc' => $qualification_meta_desc
        ]);
    }
}
