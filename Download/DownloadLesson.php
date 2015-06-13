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

        $title = $lesson->getTitle();
        $authors = "";
        foreach($lesson->getAuthors() as $author) {
            $authors .= $author.' ';
        }
        $date = $lesson->getCreatedAt()->format("m M Y");

        $raw = "Title: $title \nAuthor: $authors \nDate: $date";

        $raw .= "\r\n";
        $raw .= "\r\n";
        $raw .= "# Introduction";
        $raw .= "\r\n";
        $raw .= $lesson->getBody();

        $lenghtElement = count($lesson->getChapters());

        for($i = 0; $i < $lenghtElement; $i++) {
            $raw .= "\n\n";
            $raw .= "\n\n";
            $raw .= '#'.$lesson->getChapters()[$i]->getTitle();
            $raw .= "\n\n";
            $raw .= $lesson->getChapters()[$i]->getBody();
        }

        $lenghtElement = count($lesson->getConclusions());

        for($i = 0; $i < $lenghtElement; $i++) {
            $raw .= "\n\n";
            $raw .= "\n\n";
            $raw .= '#'.$lesson->getConclusions()[$i]->getTitle();
            $raw .= "\n\n";
            $raw .= $lesson->getConclusions()[$i]->getBody();
        }

        $options = array(
            "latex-engine" => "xelatex",
            "from"         => "markdown_github",
            "to"           => $format,
            "toc"          => null
        );

        return $pandoc->runWith($raw, $options);
    }
}
