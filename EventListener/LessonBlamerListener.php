<?php

namespace N1c0\LessonBundle\EventListener;

use N1c0\LessonBundle\Events;
use N1c0\LessonBundle\Event\LessonEvent;
use N1c0\LessonBundle\Model\SignedLessonInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Blames a lesson using Symfony2 security component
 */
class LessonBlamerListener implements EventSubscriberInterface
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
     * Assigns the currently logged in user to a Lesson.
     *
     * @param  \N1c0\LessonBundle\Event\LessonEvent $event
     * @return void
     */
    public function blame(LessonEvent $event)
    {
        $lesson = $event->getLesson();

        if (null === $this->securityContext) {
            if ($this->logger) {
                $this->logger->debug("Lesson Blamer did not receive the security.context service.");
            }

            return;
        }

        if (!$lesson instanceof SignedLessonInterface) {
            if ($this->logger) {
                $this->logger->debug("Lesson does not implement SignedLessonInterface, skipping");
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
            $lesson->setAuthor($user);
            if (!$lesson->getAuthors()->contains($user)) {
                $lesson->addAuthor($user);
            }

        }
    }

    public static function getSubscribedEvents()
    {
        return array(Events::LESSON_PRE_PERSIST => 'blame');
    }
}
