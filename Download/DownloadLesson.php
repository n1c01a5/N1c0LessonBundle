<?php

namespace N1c0\LessonBundle\Download;

use Pandoc\Pandoc;

class DownloadLesson 
{
    private $appLesson;

    public function __construct($appLesson)
    {
        $this->appLesson = $appLesson;
    }

    public function getConvert($id, $format)
    {
        $pandoc = new Pandoc();

        $lesson = $this->appLesson->findLessonById($id);

        $raw = '%'.$lesson->getTitle(); 
        $raw .= "\r\n";
        $raw .= '%'; 

        foreach($lesson->getAuthors() as $author) {
            $raw .= $author.' ;';
        }

        $raw .= "\r\n";
        $raw .= '%'.$lesson->getCreatedAt()->format("m M Y");      
        $raw .= "\r\n";
        $raw .= "# Introduction";
        $raw .= "\r\n";
        $raw .= $lesson->getBody();

        $lenghtElement = count($lesson->getChapters());

        for($i = 0; $i < $lenghtElement; $i++) {
            $raw .= "\r\n";
            $raw .= "\r\n";
            $raw .= '#'.$lesson->getChapters()[$i]->getTitle();
            $raw .= "\r\n";
            $raw .= $lesson->getChapters()[$i]->getBody();
        }

        $lenghtElement = count($lesson->getConclusions());

        for($i = 0; $i < $lenghtElement; $i++) {
            $raw .= "\r\n";
            $raw .= "\r\n";
            $raw .= '#'.$lesson->getConclusions()[$i]->getTitle();
            $raw .= "\r\n";
            $raw .= $lesson->getConclusions()[$i]->getBody();
        }

        $options = array(
            "latex-engine" => "xelatex",
            "from"         => "markdown",
            "to"           => $format,
            "toc"          => null
        );

        return $pandoc->runWith($raw, $options);
    }
}
