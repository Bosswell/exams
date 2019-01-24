<?php

namespace App\Optainer;

use Symfony\Component\DomCrawler\Crawler;

class ExamOptainer 
{
    const QUESTION_ITEM_CLASS = 'wpProQuiz_listItem';
    const LINKS_CONTAINER_CLASS = 'entry-content';

    private $parsedQuestions = Array();

    public function __construct()
    {
        libxml_use_internal_errors(true);
    }

    public function getOne(string $link) : array
    {
        $link = $this->cleanLink($link);
        $html = file_get_contents($link);

        return $this->parseExamPage($html, $link);
    }

    public function getAll(string $link)
    {
        $link = $this->cleanLink($link);
        $html = file_get_contents($link);

        $examsLinks = $this->getLinksToExams($html);

        foreach ($examsLinks as $examLink) {
            yield $this->getOne($link . '/' . $examLink->getAttribute('href'));
        }
    }

    private function parseExamPage(string $html, string $link) : Array
    {
        $parsedExam = Array();
        $questionInfo = $this->getQuestionInfo($link);

        // Get answers first because questions without images are removed
        // Changing the order threatens to break integrity
        $this->parseToGetCorrectAnswers($html, $parsedExam);
        $this->parseToGetQuestions($html, $parsedExam, $questionInfo);

        return $parsedExam;
    }

    private function parseToGetCorrectAnswers($html, &$parsedExam) : void
    {
        $crawler = new Crawler($html);

        // Get script which contain exam correct answers
        $scripts = $crawler->filter('script');

        foreach ($scripts as $script) {
            if (strpos($script->nodeValue, 'json:'))
                $string = $script->nodeValue;
        }
            
        // Cut string to json variable
        $string = substr($string, strpos($string, 'json:') + 6);

        // Remove whitespaces
        $string = preg_replace('/\s+/', '', $string);

        // Remove last 4 unnecessary characters
        $string = substr($string, 0, -4);
            
        // Transform json string into array with stdClass elements
        $correctAnswers = json_decode($string);

        // Optain correct answers
        $i = 0;
        foreach ($correctAnswers as $correctAnswer) {
            $parsedExam[$i]['id'] = $correctAnswer->id;

            foreach ($correctAnswer->correct as $key => $answer) {
                if ($answer == 1) {
                    $parsedExam[$i]['correct'] = $key + 1;
                }
            }
            $i++;
        }
    }

    private function parseToGetQuestions(string $html, &$parsedExam, $questionInfo) : void
    {
        $dom = new \DomDocument();
        $dom->loadHTML($html);
        $finder = new \DomXPath($dom);
        $class = self::QUESTION_ITEM_CLASS;
        $questions = $finder->query("//*[contains(@class, '$class')]");
        dump($questions);
        $i = 0;
        foreach ($questions as $node) {   
            $query = $node->getElementsByTagName('div')->item(2)->nodeValue;
            $answers = $node->getElementsByTagName('li');
            $img = '';

            if ($node->getElementsByTagName('img')->item(0)) {
                $img = $node->getElementsByTagName('img')->item(0)->getAttribute('src');
            }

            // Clear variables to get clean strings
            $answersArray = [
                'answer_a' => trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $answers->item(0)->nodeValue))),
                'answer_b' => trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $answers->item(1)->nodeValue))),
                'answer_c' => trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $answers->item(2)->nodeValue))),
                'answer_d' => trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $answers->item(3)->nodeValue))),
            ];

            $img = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $img)));
            
            // Get questions only without images
            if (!empty($img)) {
                unset($parsedExam[$i]);
            } else {
                // $parsed_questions[$i]['img'] = $img;
                $parsedExam[$i]['query'] = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $query)));
                $parsedExam[$i]['answers'] = $answersArray;
                $parsedExam[$i]['year'] = $questionInfo['year'];
                $parsedExam[$i]['session'] = $questionInfo['session'];
            }
            
            $i++;
        }
    }

    private function getLinksToExams(string $html) : \DOMNodeList
    {
        $dom = new \DomDocument();
        $dom->loadHTML($html);
        $finder = new \DomXPath($dom);
        $class = self::LINKS_CONTAINER_CLASS;

        $nodes = $finder->query("//*[contains(@class, '$class')]");
        $links = $nodes->item(0)->getElementsByTagName('a');

        return $links;
    }

    private function getExamInfo(string $string, Array $parsedExam) : void
    {
        $exploded_href = explode('_', $parsed);

        $parsedExam['year'] = $exploded_href[2];
        $parsedExam['session'] = $session[2];
    }

    private function getQuestionInfo(string $link) : Array
    {
        $urlParts = explode('/', $link);
        $lastUrlPart = $urlParts[count($urlParts) - 1];
        $explodedInfo = explode('_', $lastUrlPart);

        return $questionInfo = [
            'session'   =>  $explodedInfo[1],
            'year'      =>  $explodedInfo[2]    
        ];
    }

    private function cleanLink(string $link) : string
    {
        if (substr($link, -1) == '/') {
            // Remove backslash
            $link = substr($link, 0, -1);
        }

        return $link;
    }
}