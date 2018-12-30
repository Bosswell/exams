<?php

namespace App\Exam;

abstract class AbstractExam
{
	protected $questions = array();
	protected $points = 0;
	protected $question_quantity;
	
	abstract function checkQuestions();
	
	public function __construct($questions)
	{
		$this->questions = $questions;
		$this->question_quantity = count($questions);
	}
	
	public function getPoints() : int
	{
		return $this->points;
	}
	
	
	public function getPercent() : float
	{
		if ($this->question_quantity) {
			return round(($this->points / $this->question_quantity) * 100, 1);
		}

		return 0;
	}

	public function getQuestions() : array
	{
		if ($this->questions) {
			return $this->questions;
		}
	}

	public function getQuestionQuantity() : int
	{
		return $this->question_quantity;
	}
}