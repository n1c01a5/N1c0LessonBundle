<?php

namespace N1c0\LessonBundle\Acl;

use N1c0\LessonBundle\Model\ChapterInterface;
use N1c0\LessonBundle\Model\ChapterManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Wraps a real implementation of ChapterManagerInterface and
 * performs Acl checks with the configured Chapter Acl service.
 */
class AclChapterManager implements ChapterManagerInterface
{
    /**
     * The ChapterManager instance to be wrapped with ACL.
     *
     * @var ChapterManagerInterface
     */
    protected $realManager;

    /**
     * The ChapterAcl instance for checking permissions.
     *
     * @var ChapterAclInterface
     */
    protected $chapterAcl;

    /**
     * Constructor.
     *
     * @param ChapterManagerInterface $chapterManager The concrete ChapterManager service
     * @param ChapterAclInterface     $chapterAcl     The Chapter Acl service
     */
    public function __construct(ChapterManagerInterface $chapterManager, ChapterAclInterface $chapterAcl)
    {
        $this->realManager = $chapterManager;
        $this->chapterAcl  = $chapterAcl;
    }

    /**
     * {@inheritDoc}
     */
    public function all($limit = 5, $offset = 0)
    {
        $chapters = $this->realManager->all();

        if (!$this->authorizeViewChapter($chapters)) {
            throw new AccessDeniedException();
        }

        return $chapters;
    }

    /**
     * {@inheritDoc}
     */
    public function findChapterBy(array $criteria){
    }

    /**
     * {@inheritDoc}
     */
    public function findChaptersBy(array $criteria){
    }

    /**
     * {@inheritDoc}
     */
    public function findAllChapters(){
    }                 


    /**
     * {@inheritDoc}
     */
    public function saveChapter(ChapterInterface $chapter)
    {
        if (!$this->chapterAcl->canCreate()) {
            throw new AccessDeniedException();
        }

        $newChapter = $this->isNewChapter($chapter);

        if (!$newChapter && !$this->chapterAcl->canEdit($chapter)) {
            throw new AccessDeniedException();
        }

        if (($chapter::STATE_DELETED === $chapter->getState() || $chapter::STATE_DELETED === $chapter->getPreviousState())
            && !$this->chapterAcl->canDelete($chapter)
        ) {
            throw new AccessDeniedException();
        }

        $this->realManager->saveChapter($chapter);

        if ($newChapter) {
            $this->chapterAcl->setDefaultAcl($chapter);
        }
    }

    /**
     * {@inheritDoc}
     **/
    public function findChapterById($id)
    {
        $chapter = $this->realManager->findChapterById($id);

        if (null !== $chapter && !$this->chapterAcl->canView($chapter)) {
            throw new AccessDeniedException();
        }

        return $chapter;
    }

    /**
     * {@inheritDoc}
     */
    public function createChapter($id = null)
    {
        return $this->realManager->createChapter($id);
    }

    /**
     * {@inheritDoc}
     */
    public function isNewChapter(ChapterInterface $chapter)
    {
        return $this->realManager->isNewChapter($chapter);
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->realManager->getClass();
    }

    /**
     * Check if the chapter have appropriate view permissions.
     *
     * @param  array   $chapters A comment tree
     * @return boolean
     */
    protected function authorizeViewChapter(array $chapters)
    {
        foreach ($chapters as $chapter) {
            if (!$this->chapterAcl->canView($chapter)) {
                return false;
            }
        }

        return true;
    }
}
