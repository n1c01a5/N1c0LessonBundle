<?php

namespace N1c0\LessonBundle\EventListener;

use N1c0\LessonBundle\Events;
use N1c0\LessonBundle\Event\IntroductionEvent;
use N1c0\LessonBundle\Markup\ParserInterface;
use N1c0\LessonBundle\Model\RawIntroductionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Parses a introduction for markup and sets the result
 * into the rawBody property.
 *
 * @author Wagner Nicolas <contact@wagner-nicolas.com>
 */
class IntroductionMarkupListener implements EventSubscriberInterface
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
     * Parses raw introduction data and assigns it to the rawBody
     * property.
     *
     * @param \N1c0\LessonBundle\Event\IntroductionEvent $event
     */
    public function markup(IntroductionEvent $event)
    {
        $introduction = $event->getIntroduction();

        if (!$introduction instanceof RawIntroductionInterface) {
            return;
        }

        $result = $this->parser->parse($introduction->getBody());
        $introduction->setRawBody($result);
    }

    public static function getSubscribedEvents()
    {
        return array(Events::INTRODUCTION_PRE_PERSIST => 'markup');
    }
}
