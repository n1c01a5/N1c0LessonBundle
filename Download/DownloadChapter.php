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
        $title = $chapter->getTitle();
        $authors = "";
        foreach($chapter->getAuthors() as $author) {
            $authors .= $author.' ;';
        }
        $date = $chapter->getCreatedAt()->format("m M Y");

        $raw = "Title: $title \nAuthor: $authors \nDate: $date";
        $raw .= "\r\n";
        $raw .= "\r\n";
        $raw .= $chapter->getBody();


        $options = array(
            "latex-engine" => "xelatex",
            "from"         => "markdown_github",
            "to"           => $format
        );

        return  $pandoc->runWith($raw, $options);
    }
}
