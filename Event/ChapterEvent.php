<?php

namespace N1c0\LessonBundle\Event;

use N1c0\LessonBundle\Model\ChapterInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a chapter.
 */
class ChapterEvent extends Event
{
    private $chapter;

    /**
     * Constructs an event.
     *
     * @param \n1c0\LessonBundle\Model\ChapterInterface $chapter
     */
    public function __construct(ChapterInterface $chapter)
    {
        $this->chapter = $chapter;
    }

    /**
     * Returns the chapter for this event.
     *
     * @return \n1c0\LessonBundle\Model\ChapterInterface
     */
    public function getChapter()
    {
        return $this->chapter;
    }
}
