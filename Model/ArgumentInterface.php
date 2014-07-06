<?php

namespace N1c0\LessonBundle\Model;

Interface ArgumentInterface
{
    /**
     * @return mixed unique ID for this argument
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
     * @return ArgumentInterface
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
     * @return ArgumentInterface
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
     * @return ChapterInterface
     */
    public function getChapter();

    /**
     * @param ChapterInterface $chapter
     */
    public function setChapter(ChapterInterface $chapter);
}
