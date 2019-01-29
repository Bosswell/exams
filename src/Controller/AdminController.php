<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Optainer\ExamOptainer;
use App\Entity\Qualification;
use App\Entity\Question;

class AdminController extends BaseAdminController
{
    /**
     * @Route("/cache/clear", name="clear_cache")
     */
    public function clearCache(Request $request)
    {
        $cache = new FilesystemCache();
        $cache->clear();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/exams/optainer", name="optain_exam")
     */
    public function optainExam(Request $request)
    {
        $session = new Session();

        $em = $this->getDoctrine()->getManager();
        $qualifications = $em->getRepository(Qualification::class)->findAll();

        foreach ($session->getFlashBag()->get('notice', []) as $message) {
            echo '<div class="flash-notice">'.$message.'</div>';
        }

        return $this->render('/admin/examsOptainer.html.twig', [
            'qualifications'    =>  $qualifications
        ]);
    }

    /**
     * @Route("/exams/optainer/parse", name="parse_exam")
     */
    public function parseExam(Request $request)
    {
        $session = new Session();

        $examsLink = $request->get('exams_link');
        $examLink = $request->get('exam_link');
        $qualification_id = $request->get('qualification_id');
        
        $em = $this->getDoctrine()->getManager();
        $qualification = $em->getRepository(Qualification::class)->find($qualification_id);
        $examOptainer = new ExamOptainer();

        $i = 1;

        if (!empty($examsLink)) {
            foreach ($examOptainer->getAll($examsLink) as $exam) {
                foreach ($exam as $external_question) {
                    $question = new Question();
                    $question->setQuery($external_question['query']);
                    $question->setAnswerA($external_question['answers']['answer_a']);
                    $question->setAnswerB($external_question['answers']['answer_b']);
                    $question->setAnswerC($external_question['answers']['answer_c']);
                    $question->setAnswerD($external_question['answers']['answer_d']);
                    $question->setCreatedAt(new \DateTime('now'));
                    $question->setCorrect($external_question['correct']);
                    $question->setYear($external_question['year']);
                    $question->setSession($external_question['session']);
                    $question->setExternalId($external_question['id']);
                    $question->setQualification($qualification);
        
                    $em->persist($question);
        
                    $i++;
                }
            }
    
            $em->flush();

        } else {
            $exam = $examOptainer->getOne($examLink);

            foreach ($exam as $external_question) {
                $question = new Question();
                $question->setQuery($external_question['query']);
                $question->setAnswerA($external_question['answers']['answer_a']);
                $question->setAnswerB($external_question['answers']['answer_b']);
                $question->setAnswerC($external_question['answers']['answer_c']);
                $question->setAnswerD($external_question['answers']['answer_d']);
                $question->setCreatedAt(new \DateTime('now'));
                $question->setCorrect($external_question['correct']);
                $question->setYear($external_question['year']);
                $question->setSession($external_question['session']);
                $question->setExternalId($external_question['id']);
                $question->setQualification($qualification);
    
                $em->persist($question);
    
                $i++;
            }
        }

        $session->getFlashBag()->add('notice', 'Dodano '. $i . ' pytań');
        
        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}