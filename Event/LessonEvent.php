<?php

namespace N1c0\LessonBundle\Event;

use N1c0\LessonBundle\Model\LessonInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a lesson.
 */
class LessonEvent extends Event
{
    private $lesson;

    /**
     * Constructs an event.
     *
     * @param \N1c0\LessonBundle\Model\LessonInterface $lesson
     */
    public function __construct(LessonInterface $lesson)
    {
        $this->lesson = $lesson;
    }

    /**
     * Returns the lesson for this event.
     *
     * @return \N1c0\LessonBundle\Model\LessonInterface
     */
    public function getLesson()
    {
        return $this->lesson;
    }
}
