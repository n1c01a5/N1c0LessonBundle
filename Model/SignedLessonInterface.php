<?php

namespace N1c0\LessonBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * A signed lesson is bound to a FOS\UserBundle User model.
 */
interface SignedLessonInterface extends LessonInterface
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
     * Gets the authors of the Lesson
     *
     * @return UserInterface
     */
    public function getAuthors();

    /**
     * Gets the last author of the Lesson
     *
     * @return UserInterface
     */
    public function getAuthor();
}

