<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Exam\Exam;

class ExamController extends AbstractController
{
    /**
     * @Route("/egzamin", name="generate_exam")
     */
    public function generateExamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qualification_id = $request->get('qualification_id');
        $question_quantity = $request->get('question_quantity');

        $qualification = $em->getRepository('App:Qualification')->find($qualification_id);
        $questions = $em->getRepository('App:Question')
                        ->getRandomEntities($qualification_id, $question_quantity);
                    
        $exam = new Exam($questions);

        if (!$questions) {
            throw new NotFoundHttpException("Page not found");
        }

        $request->getSession()->set('exam', $exam);

        return $this->render('exam/show.html.twig', [
            'questions'  => $questions,
            'qualification'    =>  $qualification,
            'question_quantity'    =>  $question_quantity
        ]);
    }

    /**
     * @Route("/egzamin/sprawdz", name="check_exam")
     */
    public function checkExamAction(Request $request)
    {
        $exam = $request->getSession()->get('exam');
        $current_stage = $request->getSession()->get('current_stage');

        if (!$exam) {
            return $this->redirectToRoute('home_page');
        }

        $answers = $request->request->all();
        $exam->setAnswers($answers['answers'])
            ->checkQuestions();

        //add to history
        //if ($this->getUser() && $qualification_id) {
        //    $em = $this->getDoctrine()->getManager();

        //    $qualification = $em->getRepository('AppBundle:Qualification')->find($qualification_id);

        //    $history = ( new History() )
        //        ->setQualification($qualification)
        //        ->setUser($this->getUser())
        //        ->setPoints($exam->getPoints());


        //    $em->persist($history);
        //    $em->flush();
        //}

        $request->getSession()->remove('exam');
        $request->getSession()->remove('current_stage');
        
        return $this->render('exam/summary.html.twig', [
            'questions'         => $exam->getQuestions(),
            'points'            => $exam->getPoints(),
            'percent'           => $exam->getPercent(),
            'question_quantity' => $exam->getQuestionQuantity(),
            'current_stage'     => $current_stage
        ]);
    }
}
