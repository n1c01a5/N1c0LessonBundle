<?php

namespace N1c0\LessonBundle\Acl;

use N1c0\ChapterBundle\Model\ChapterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Implements Role checking using the Symfony2 Security component
 */
class RoleChapterAcl implements ChapterAclInterface
{
    /**
     * The current Security Context.
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * The FQCN of the Chapter object.
     *
     * @var string
     */
    private $chapterClass;

    /**
     * The role that will grant create permission for a chapter.
     *
     * @var string
     */
    private $createRole;

    /**
     * The role that will grant view permission for a chapter.
     *
     * @var string
     */
    private $viewRole;

    /**
     * The role that will grant edit permission for a chapter.
     *
     * @var string
     */
    private $editRole;

    /**
     * The role that will grant delete permission for a chapter.
     *
     * @var string
     */
    private $deleteRole;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param string                   $createRole
     * @param string                   $viewRole
     * @param string                   $editRole
     * @param string                   $deleteRole
     * @param string                   $chapterClass
     */
    public function __construct(SecurityContextInterface $securityContext,
                                $createRole,
                                $viewRole,
                                $editRole,
                                $deleteRole,
                                $chapterClass
    )
    {
        $this->securityContext   = $securityContext;
        $this->createRole        = $createRole;
        $this->viewRole          = $viewRole;
        $this->editRole          = $editRole;
        $this->deleteRole        = $deleteRole;
        $this->chapterClass      = $chapterClass;
    }

    /**
     * Checks if the Security token has an appropriate role to create a new Chapter.
     *
     * @return boolean
     */
    public function canCreate()
    {
        return $this->securityContext->isGranted($this->createRole);
    }

    /**
     * Checks if the Security token is allowed to view the specified Chapter.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canView(ChapterInterface $chapter)
    {
        return $this->securityContext->isGranted($this->viewRole);
    }

    /**
     * Checks if the Security token is allowed to reply to a parent chapter.
     *
     * @param  ChapterInterface|null $parent
     * @return boolean
     */
    public function canReply(ChapterInterface $parent = null)
    {
        if (null !== $parent) {
            return $this->canCreate() && $this->canView($parent);
        }

        return $this->canCreate();
    }

    /**
     * Checks if the Security token has an appropriate role to edit the supplied Chapter.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canEdit(ChapterInterface $chapter)
    {
        return $this->securityContext->isGranted($this->editRole);
    }

    /**
     * Checks if the Security token is allowed to delete a specific Chapter.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canDelete(ChapterInterface $chapter)
    {
        return $this->securityContext->isGranted($this->deleteRole);
    }

    /**
     * Role based Acl does not require setup.
     *
     * @param  ChapterInterface $chapter
     * @return void
     */
    public function setDefaultAcl(ChapterInterface $chapter)
    {

    }

    /**
     * Role based Acl does not require setup.
     *
     * @return void
     */
    public function installFallbackAcl()
    {

    }

    /**
     * Role based Acl does not require setup.
     *
     * @return void
     */
    public function uninstallFallbackAcl()
    {

    }
}
