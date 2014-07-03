<?php

namespace N1c0\LessonBundle\Download;

use Pandoc\Pandoc;

class DownloadIntroduction 
{
    private $appIntroduction;

    public function __construct($appIntroduction)
    {
        $this->appIntroduction = $appIntroduction;
    }

    public function getConvert($id, $format)
    {
        $pandoc = new Pandoc();

        $introduction = $this->appIntroduction->findIntroductionById($id);

        $raw = '%'.$introduction->getTitle(); 
        $raw .= "\r\n";
        $raw .= '%';

        foreach($introduction->getAuthors() as $author) {
            $raw .= $author.' ;';
        }

        $raw .= "\r\n";
        $raw .= '%'.$introduction->getCreatedAt()->format("m M Y");      
        $raw .= "\r\n";
        $raw .= $introduction->getBody();


        $options = array(
            "latex-engine" => "xelatex",
            "from"         => "markdown",
            "to"           => $format
        );

        return  $pandoc->runWith($raw, $options);
    }
}
