<?php

namespace N1c0\LessonBundle\Acl;

use N1c0\LessonBundle\Model\LessonInterface;
use N1c0\LessonBundle\Model\LessonManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Wraps a real implementation of LessonManagerInterface and
 * performs Acl checks with the configured Lesson Acl service.
 */
class AclLessonManager implements LessonManagerInterface
{
    /**
     * The LessonManager instance to be wrapped with ACL.
     *
     * @var LessonManagerInterface
     */
    protected $realManager;

    /**
     * The LessonAcl instance for checking permissions.
     *
     * @var LessonAclInterface
     */
    protected $lessonAcl;

    /**
     * Constructor.
     *
     * @param LessonManagerInterface $lessonManager The concrete LessonManager service
     * @param LessonAclInterface     $lessonAcl     The Lesson Acl service
     */
    public function __construct(LessonManagerInterface $lessonManager, LessonAclInterface $lessonAcl)
    {
        $this->realManager = $lessonManager;
        $this->lessonAcl  = $lessonAcl;
    }

    /**
     * {@inheritDoc}
     */
    public function all($limit = 5, $offset = 0)
    {
        $lessons = $this->realManager->all();

        if (!$this->authorizeViewLesson($lessons)) {
            throw new AccessDeniedException();
        }

        return $lessons;
    }

    /**
     * {@inheritDoc}
     */
    public function findLessonBy(array $criteria){
    }

    /**
     * {@inheritDoc}
     */
    public function findLessonsBy(array $criteria){
    }

    /**
     * {@inheritDoc}
     */
    public function findAllLessons(){
    }                 


    /**
     * {@inheritDoc}
     */
    public function saveLesson(LessonInterface $lesson)
    {
        if (!$this->lessonAcl->canCreate()) {
            throw new AccessDeniedException();
        }

        $newLesson = $this->isNewLesson($lesson);

        if (!$newLesson && !$this->lessonAcl->canEdit($lesson)) {
            throw new AccessDeniedException();
        }

        if (($lesson::STATE_DELETED === $lesson->getState() || $lesson::STATE_DELETED === $lesson->getPreviousState())
            && !$this->lessonAcl->canDelete($lesson)
        ) {
            throw new AccessDeniedException();
        }

        $this->realManager->saveLesson($lesson);

        if ($newLesson) {
            $this->lessonAcl->setDefaultAcl($lesson);
        }
    }

    /**
     * {@inheritDoc}
     **/
    public function findLessonById($id)
    {
        $lesson = $this->realManager->findLessonById($id);

        if (null !== $lesson && !$this->lessonAcl->canView($lesson)) {
            throw new AccessDeniedException();
        }

        return $lesson;
    }

    /**
     * {@inheritDoc}
     */
    public function createLesson($id = null)
    {
        return $this->realManager->createLesson($id);
    }

    /**
     * {@inheritDoc}
     */
    public function isNewLesson(LessonInterface $lesson)
    {
        return $this->realManager->isNewLesson($lesson);
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->realManager->getClass();
    }

    /**
     * Check if the lesson have appropriate view permissions.
     *
     * @param  array   $lessons A comment tree
     * @return boolean
     */
    protected function authorizeViewLesson(array $lessons)
    {
        foreach ($lessons as $lesson) {
            if (!$this->lessonAcl->canView($lesson)) {
                return false;
            }
        }

        return true;
    }
}
