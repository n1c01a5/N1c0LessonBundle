<?php

namespace N1c0\LessonBundle\Model;

use DateTime;

/**
 * Storage agnostic introduction lesson object
 */
abstract class Introduction implements IntroductionInterface
{
    /**
     * Introduction id 
     *
     * @var mixed
     */
    protected $id;

    /**
     * Title
     *
     * @var string
     */
    protected $title;

    /**
     * Body
     *
     * @var string
     */
    protected $body;
    
    /**
     * Should be mapped by the end developer.
     *
     * @var LessonInterface
     */
    protected $lesson;

    /**
     * CommitTitle
     *
     * @var string
     */
    protected $commitTitle;

    /**
     * CommitBody
     *
     * @var string
     */
    protected $commitBody;

    /**
     * @var DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param  string
     * @return null
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param  string
     * @return null
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return LessonInterface
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * @param LessonInterface $lesson
     *
     * @return void
     */
    public function setLesson(LessonInterface $lesson)
    {
        $this->lesson = $lesson;
    }

    /**
     * @return string
     */
    public function getCommitBody()
    {
        return $this->commitBody;
    }

    /**
     * @param  string
     * @return null
     */
    public function setCommitBody($commitBody)
    {
        $this->commitBody = $commitBody;
    }

    /**
     * @return string
     */
    public function getCommitTitle()
    {
        return $this->commitTitle;
    }

    /**
     * @param  string
     * @return null
     */
    public function setCommitTitle($commitTitle)
    {
        $this->commitTitle = $commitTitle;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the creation date
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return array with the names of the introduction authors
     */
    public function getAuthorsName()
    {
        return 'Anonymous';
    }

    /**
     * @return array with the name of the introduction author
     */
    public function getAuthorName()
    {
        return 'Anonymous';
    }

    public function __toString()
    {
        return 'Introduction #'.$this->getId();
    }
}
