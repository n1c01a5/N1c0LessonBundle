<?php

namespace N1c0\LessonBundle\Model;

Interface LessonInterface
{
    const STATE_VISIBLE = 0;

    const STATE_DELETED = 1;

    const STATE_SPAM = 2;

    const STATE_PENDING = 3;

    /**
     * @return mixed unique ID for this lesson
     */
    public function getId();

    /**
     * @return array with authors of the lesson
     */
    public function getAuthorsName();

    /**
     * @return array with the last author of the lesson
     */
    public function getAuthorName();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();
    
    /**
     * Set title
     *
     * @param string $title
     * @return LessonInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle();

    /**
     * Set body
     *
     * @param string $body
     * @return LessonInterface
     */
    public function setBody($body);

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody();

    /**
     * Set commitTitle
     *
     * @param string $commitTitle
     * @return LessonInterface
     */
    public function setCommitTitle($commitTitle);

    /**
     * Get commitTitle
     *
     * @return string 
     */
    public function getCommitTitle();

    /**
     * Set commitBody
     *
     * @param string $commitBody
     * @return LessonInterface
     */
    public function setCommitBody($commitBody);

    /**
     * Get commitBody
     *
     * @return string 
     */
    public function getCommitBody();

    /**
     * @return integer The current state of the comment
     */
    public function getState();

    /**
     * @param integer state
     */
    public function setState($state);

    /**
     * Gets the previous state.
     *
     * @return integer
     */
    public function getPreviousState();
}
