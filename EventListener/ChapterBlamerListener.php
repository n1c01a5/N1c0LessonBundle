<?php

namespace N1c0\LessonBundle\EventListener;

use N1c0\LessonBundle\Events;
use N1c0\LessonBundle\Event\ChapterEvent;
use N1c0\LessonBundle\Model\SignedChapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Blames a chapter using Symfony2 security component
 */
class ChapterBlamerListener implements EventSubscriberInterface
{
    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param LoggerInterface          $logger
     */
    public function __construct(SecurityContextInterface $securityContext = null, LoggerInterface $logger = null)
    {
        $this->securityContext = $securityContext;
        $this->logger = $logger;
    }

    /**
     * Assigns the currently logged in user to a Chapter.
     *
     * @param  \N1c0\LessonBundle\Event\ChapterEvent $event
     * @return void
     */
    public function blame(ChapterEvent $event)
    {
        $chapter = $event->getChapter();

        if (null === $this->securityContext) {
            if ($this->logger) {
                $this->logger->debug("Chapter Blamer did not receive the security.context service.");
            }

            return;
        }

        if (!$chapter instanceof SignedChapterInterface) {
            if ($this->logger) {
                $this->logger->debug("Chapter does not implement SignedChapterInterface, skipping");
            }

            return;
        }

        if (null === $this->securityContext->getToken()) {
            if ($this->logger) {
                $this->logger->debug("There is no firewall configured. We cant get a user.");
            }

            return;
        }

        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->securityContext->getToken()->getUser();
            $chapter->setAuthor($user);
            if (!$chapter->getAuthors()->contains($user)) {
                $chapter->addAuthor($user);
            }
            if (!$chapter->getLesson()->getAuthors()->contains($user)) {
                $chapter->getLesson()->addAuthor($user);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(Events::CHAPTER_PRE_PERSIST => 'blame');
    }
}
