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
     * @Route("/egzamin/generator/{friendly_stage_url}", name="redirect_exam")
     */
    public function selectQualificationsFormRedirection(Request $request, QualificationGenerator $qualificationGenerator, $friendly_stage_url)
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
            'friendly_qualification_url' => $friendly_qualification_url
        ], 302);
    }

    /**
     * @Route("/egzamin/{question_quantity}/{friendly_stage_url}/{qualification_id}-{friendly_qualification_url}", name="generate_exam")
     */
    public function generateExam(Request $request, $qualification_id, $question_quantity)
    {
        if (empty($qualification_id) || empty($question_quantity)) {
            return $this->redirectToRoute('homepage', null, 302);
        }

        if (!ExamValidator::isCorrectNumberOfQuestions($question_quantity)) {
            return $this->redirectToRoute('homepage', null, 302);
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

        return $this->render('exam/show.html.twig', [
            'questions'  => $questions,
            'qualification'    =>  $qualification,
            'question_quantity'    =>  $question_quantity
        ]);
    }

    /**
     * @Route("/egzamin/sprawdz", name="check_exam")
     */
    public function checkExam(Request $request)
    {
        $exam = $request->getSession()->get('exam');

        if (empty($exam)) {
            return $this->redirectToRoute('home_page');
        }

        $current_stage_id = $request->getSession()->get('current_stage_id');

        $answers = $request->request->all();
        $exam->setAnswers($answers['answers'])
            ->checkQuestions();

        // $request->getSession()->remove('exam');
        // $request->getSession()->remove('current_stage_id');
        
        return $this->render('exam/summary.html.twig', [
            'questions'         => $exam->getQuestions(),
            'points'            => $exam->getPoints(),
            'percent'           => $exam->getPercent(),
            'question_quantity' => $exam->getQuestionQuantity(),
            'current_stage_id'  => $current_stage_id
        ]);
    }
}
