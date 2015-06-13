<?php

namespace N1c0\LessonBundle\Download;

use Pandoc\Pandoc;

class DownloadConclusion
{
    private $appConclusion;

    public function __construct($appConclusion)
    {
        $this->appConclusion = $appConclusion;
    }

    public function getConvert($id, $format)
    {
        $pandoc = new Pandoc();

        $conclusion = $this->appConclusion->findConclusionById($id);

        $title = $conclusion->getTitle();
        $authors = "";
        foreach($conclusion->getAuthors() as $author) {
            $authors = $author.' ';
        }
        $date = $conclusion->getCreatedAt()->format("m M Y");
        $raw = "Title: $title \nAuthor: $authors \nDate: $date";
        $raw .= "\n\n";
        $raw .= $conclusion->getBody();


        $options = array(
            "latex-engine" => "xelatex",
            "from"         => "markdown_github",
            "to"           => $format
        );

        return  $pandoc->runWith($raw, $options);
    }
}
