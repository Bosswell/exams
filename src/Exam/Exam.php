<?php

namespace App\Exam;

use App\Exam\AbstractExam;
use App\Exam\ExamValidator;
use App\Entity\Question;

class Exam extends AbstractExam
{
    private $answers = array();

	public function checkQuestions() : void
	{  
		for ($i = 0; $i < $this->question_quantity; $i++) {
            if (ExamValidator::isQuestionCorrect($this->questions[$i], $this->answers[$i])) {
                $this->points++;
            }
        }
	}

    public function setAnswers(array $answers) : self
    {
        $this->answers = $answers;

        return $this;
    }
}