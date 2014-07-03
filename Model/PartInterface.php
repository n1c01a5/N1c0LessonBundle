<?php

namespace N1c0\LessonBundle\Model;

Interface PartInterface
{
    /**
     * @return mixed unique ID for this part
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
     * @return PartInterface
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
     * @return PartInterface
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
