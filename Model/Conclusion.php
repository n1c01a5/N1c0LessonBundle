<?php

namespace N1c0\LessonBundle\Model;

use DateTime;

/**
 * Storage agnostic conclusion lesson object
 */
abstract class Conclusion implements ConclusionInterface
{
    /**
     *Conclusion id
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

    /**
     * Current state of the conclusion.
     *
     * @var integer
     */
    protected $state = 0;

    /**
     * The previous state of the conclusion.
     *
     * @var integer
     */
    protected $previousState = 0;

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
     * @return array with the names of the conclusion authors
     */
    public function getAuthorsName()
    {
        return 'Anonymous';
    }

    /**
     * @return array with the name of the conclusion author
     */
    public function getAuthorName()
    {
        return 'Anonymous';
    }

    /**
     * {@inheritDoc}
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * {@inheritDoc}
     */
    public function setState($state)
    {
        $this->previousState = $this->state;
        $this->state = $state;
    }

    /**
     * {@inheritDoc}
     */
    public function getPreviousState()
    {
        return $this->previousState;
    }

    public function __toString()
    {
        return 'Conclusion #'.$this->getId();
    }
}
