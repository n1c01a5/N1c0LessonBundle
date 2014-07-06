<?php

namespace N1c0\LessonBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * A signed chapter is bound to a FOS\UserBundle User model.
 */
interface SignedChapterInterface extends ChapterInterface
{
    /**
     * Add user 
     *
     * @param Application\UserBundle\Entity\User $user
     */
    public function addAuthor(\Application\UserBundle\Entity\User $user);

    /**
     * Remove user
     *
     * @param Application\UserBundle\Entity\User $user
     */
    public function removeUser(\Application\UserBundle\Entity\User $user);

    /**
     * Gets the authors of the Chapter
     *
     * @return UserInterface
     */
    public function getAuthors();

    /**
     * Gets the lasr author of the Chapter
     *
     * @return UserInterface
     */
    public function getAuthor();
}

