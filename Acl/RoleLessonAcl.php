<?php

namespace N1c0\LessonBundle\Acl;

use N1c0\LessonBundle\Model\LessonInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Implements Role checking using the Symfony2 Security component
 */
class RoleLessonAcl implements LessonAclInterface
{
    /**
     * The current Security Context.
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * The FQCN of the Lesson object.
     *
     * @var string
     */
    private $lessonClass;

    /**
     * The role that will grant create permission for a lesson.
     *
     * @var string
     */
    private $createRole;

    /**
     * The role that will grant view permission for a lesson.
     *
     * @var string
     */
    private $viewRole;

    /**
     * The role that will grant edit permission for a lesson.
     *
     * @var string
     */
    private $editRole;

    /**
     * The role that will grant delete permission for a lesson.
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
     * @param string                   $lessonClass
     */
    public function __construct(SecurityContextInterface $securityContext,
                                $createRole,
                                $viewRole,
                                $editRole,
                                $deleteRole,
                                $lessonClass
    )
    {
        $this->securityContext   = $securityContext;
        $this->createRole        = $createRole;
        $this->viewRole          = $viewRole;
        $this->editRole          = $editRole;
        $this->deleteRole        = $deleteRole;
        $this->lessonClass      = $lessonClass;
    }

    /**
     * Checks if the Security token has an appropriate role to create a new Lesson.
     *
     * @return boolean
     */
    public function canCreate()
    {
        return $this->securityContext->isGranted($this->createRole);
    }

    /**
     * Checks if the Security token is allowed to view the specified Lesson.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canView(LessonInterface $lesson)
    {
        return $this->securityContext->isGranted($this->viewRole);
    }

    /**
     * Checks if the Security token is allowed to reply to a parent lesson.
     *
     * @param  LessonInterface|null $parent
     * @return boolean
     */
    public function canReply(LessonInterface $parent = null)
    {
        if (null !== $parent) {
            return $this->canCreate() && $this->canView($parent);
        }

        return $this->canCreate();
    }

    /**
     * Checks if the Security token has an appropriate role to edit the supplied Lesson.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canEdit(LessonInterface $lesson)
    {
        return $this->securityContext->isGranted($this->editRole);
    }

    /**
     * Checks if the Security token is allowed to delete a specific Lesson.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canDelete(LessonInterface $lesson)
    {
        return $this->securityContext->isGranted($this->deleteRole);
    }

    /**
     * Role based Acl does not require setup.
     *
     * @param  LessonInterface $lesson
     * @return void
     */
    public function setDefaultAcl(LessonInterface $lesson)
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
