<?php

namespace N1c0\LessonBundle\Event;

/**
 * An event related to a persisting event that can be
 * cancelled by a listener.
 */
class ChapterPersistEvent extends ChapterEvent
{
    /**
     * @var bool
     */
    private $abortPersistence = false;

    /**
     * Indicates that the persisting operation should not proceed.
     */
    public function abortPersistence()
    {
        $this->abortPersistence = true;
    }

    /**
     * Checks if a listener has set the event to abort the persisting
     * operation.
     *
     * @return bool
     */
    public function isPersistenceAborted()
    {
        return $this->abortPersistence;
    }
}
