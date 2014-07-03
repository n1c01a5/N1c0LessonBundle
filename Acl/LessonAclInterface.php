<?php

namespace N1c0\LessonBundle\Acl;

use N1c0\LessonBundle\Model\LessonInterface;

/**
 * Used for checking if the ACL system will allow specific actions
 * to occur.
 */
interface LessonAclInterface
{
    /**
     * Checks if the user should be able to create a lesson.
     *
     * @return boolean
     */
    public function canCreate();

    /**
     * Checks if the user should be able to view a lesson.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canView(LessonInterface $lesson);

    /**
     * Checks if the user can reply to the supplied 'parent' lesson
     * or if not supplied, just the ability to reply.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canReply(LessonInterface $parent = null);

    /**
     * Checks if the user should be able to edit a lesson.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canEdit(LessonInterface $lesson);

    /**
     * Checks if the user should be able to delete a lesson.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canDelete(LessonInterface $lesson);

    /**
     * Sets the default Acl permissions on a lesson.
     *
     * Note: this does not remove any existing Acl and should only
     * be called on new LessonInterface instances.
     *
     * @param  LessonInterface $lesson
     * @return void
     */
    public function setDefaultAcl(LessonInterface $lesson);

    /**
     * Installs the Default 'fallback' Acl entries for generic access.
     *
     * @return void
     */
    public function installFallbackAcl();

    /**
     * Removes default Acl entries
     * @return void
     */
    public function uninstallFallbackAcl();
}
