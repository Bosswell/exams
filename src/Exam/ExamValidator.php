<?php

namespace App\Exam;

use App\Entity\Question;

class ExamValidator
{
    static public function isQuestionCorrect(Question $question, int $answer) : bool
    {
        $question->setUserAnswer($answer);

        if ($question->getCorrect() == $answer) {
            return true;
        }

        return false;
    }

    static public function isCorrectNumberOfQuestions(int $question_quantity) : bool 
    {
        $allowed_question_quantity = [
            5, 10, 20, 40
        ];

        return in_array($question_quantity, $allowed_question_quantity);
    }
}