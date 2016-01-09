<?php

namespace N1c0\LessonBundle\Acl;

use N1c0\LessonBundle\Model\ChapterInterface;

/**
 * Used for checking if the ACL system will allow specific actions
 * to occur.
 */
interface ChapterAclInterface
{
    /**
     * Checks if the user should be able to create a chapter.
     *
     * @return boolean
     */
    public function canCreate();

    /**
     * Checks if the user should be able to view a chapter.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canView(ChapterInterface $chapter);

    /**
     * Checks if the user can reply to the supplied 'parent' chapter
     * or if not supplied, just the ability to reply.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canReply(ChapterInterface $parent = null);

    /**
     * Checks if the user should be able to edit a chapter.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canEdit(ChapterInterface $chapter);

    /**
     * Checks if the user should be able to delete a chapter.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canDelete(ChapterInterface $chapter);

    /**
     * Sets the default Acl permissions on a chapter.
     *
     * Note: this does not remove any existing Acl and should only
     * be called on new ChapterInterface instances.
     *
     * @param  ChapterInterface $chapter
     * @return void
     */
    public function setDefaultAcl(ChapterInterface $chapter);

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
