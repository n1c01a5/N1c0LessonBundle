<?php

namespace N1c0\LessonBundle\Event;

use N1c0\LessonBundle\Model\ConclusionInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a conclusion.
 */
class ConclusionEvent extends Event
{
    private $conclusion;

    /**
     * Constructs an event.
     *
     * @param \n1c0\LessonBundle\Model\ConclusionInterface $conclusion
     */
    public function __construct(ConclusionInterface $conclusion)
    {
        $this->conclusion = $conclusion;
    }

    /**
     * Returns the conclusion for this event.
     *
     * @return \n1c0\LessonBundle\Model\ConclusionInterface
     */
    public function getConclusion()
    {
        return $this->conclusion;
    }
}
