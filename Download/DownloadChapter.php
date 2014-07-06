<?php

namespace N1c0\LessonBundle\Download;

use Pandoc\Pandoc;

class DownloadChapter 
{
    private $appChapter;

    public function __construct($appChapter)
    {
        $this->appChapter = $appChapter;
    }

    public function getConvert($id, $format)
    {
        $pandoc = new Pandoc();

        $chapter = $this->appChapter->findChapterById($id);

        $raw = '% efez'.$chapter->getTitle(); 
        $raw .= "\r\n";
        $raw .= '%'; 

        foreach($chapter->getAuthors() as $author) {
            $raw .= $author.' ;';
        }

        $raw .= "\r\n";
        $raw .= '%'.$chapter->getCreatedAt()->format("m M Y");      
        $raw .= "\r\n";
        $raw .= $chapter->getBody();


        $options = array(
            "latex-engine" => "xelatex",
            "from"         => "markdown",
            "to"           => $format
        );

        return  $pandoc->runWith($raw, $options);
    }
}
