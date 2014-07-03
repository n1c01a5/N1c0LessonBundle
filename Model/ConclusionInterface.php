<?php

namespace N1c0\LessonBundle\Model;

Interface ConclusionInterface
{
    /**
     * @return mixed unique ID for this conclusion
     */
    public function getId();

    /**
     * @return array with authors of the lesson
     */
    public function getAuthorsName();
    
    /**
     * Set title
     *
     * @param string $title
     * @return ConclusionInterface
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
     * @return ConclusionInterface
     */
    public function setBody($body);

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return LessonInterface
     */
    public function getLesson();

    /**
     * @param LessonInterface $lesson
     */
    public function setLesson(LessonInterface $lesson);
}
