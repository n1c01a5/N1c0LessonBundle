<?php

namespace N1c0\LessonBundle\EventListener;

use N1c0\LessonBundle\Events;
use N1c0\LessonBundle\Event\ChapterEvent;
use N1c0\LessonBundle\Markup\ParserInterface;
use N1c0\LessonBundle\Model\RawChapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Parses a chapter for markup and sets the result
 * into the rawBody property.
 *
 * @author Wagner Nicolas <contact@wagner-nicolas.com>
 */
class ChapterMarkupListener implements EventSubscriberInterface
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
     * Parses raw chapter data and assigns it to the rawBody
     * property.
     *
     * @param \N1c0\LessonBundle\Event\ChapterEvent $event
     */
    public function markup(ChapterEvent $event)
    {
        $chapter = $event->getChapter();

        if (!$chapter instanceof RawChapterInterface) {
            return;
        }

        $result = $this->parser->parse($chapter->getBody());
        $chapter->setRawBody($result);
    }

    public static function getSubscribedEvents()
    {
        return array(Events::CHAPTER_PRE_PERSIST => 'markup');
    }
}
