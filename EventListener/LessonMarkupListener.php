<?php

namespace N1c0\LessonBundle\EventListener;

use N1c0\LessonBundle\Events;
use N1c0\LessonBundle\Event\LessonEvent;
use N1c0\LessonBundle\Markup\ParserInterface;
use N1c0\LessonBundle\Model\RawLessonInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Parses a lesson for markup and sets the result
 * into the rawBody property.
 *
 * @author Wagner Nicolas <contact@wagner-nicolas.com>
 */
class LessonMarkupListener implements EventSubscriberInterface
{
    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * Constructor.
     *
     * @param \N1c0\LessonBundle\Markup\ParserInterface $parser
     */
    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Parses raw lesson data and assigns it to the rawBody
     * property.
     *
     * @param \N1c0\LessonBundle\Event\LessonEvent $event
     */
    public function markup(LessonEvent $event)
    {
        $lesson = $event->getLesson();

        if (!$lesson instanceof RawLessonInterface) {
            return;
        }

        $result = $this->parser->parse($lesson->getBody());
        $lesson->setRawBody($result);
    }

    public static function getSubscribedEvents()
    {
        return array(Events::LESSON_PRE_PERSIST => 'markup');
    }
}
