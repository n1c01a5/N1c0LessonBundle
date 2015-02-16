<?php

namespace N1c0\LessonBundle\Model;

use N1c0\LessonBundle\Events;
use N1c0\LessonBundle\Event\ChapterEvent;
use N1c0\LessonBundle\Event\ChapterPersistEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use InvalidChapterException;

/**
 * Abstract Chapter Manager implementation which can be used as base class for your
 * concrete manager.
 */
abstract class ChapterManager implements ChapterManagerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Constructor
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get a list of Chapters.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit, $offset)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * @param  string          $id
     * @return ChapterInterface
     */
    public function findChapterById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Returns an empty chapter instance
     *
     * @return Chapter
     */
    public function createChapter(LessonInterface $lesson)
    {
        $class = $this->getClass();
        $chapter = new $class;

        $chapter->setLesson($lesson);

        $event = new ChapterEvent($chapter);
        $this->dispatcher->dispatch(Events::CHAPTER_CREATE, $event);

        return $chapter;
    }

    /**
     * Saves a chapter to the persistence backend used. Each backend
     * must implement the abstract doSaveChapter method which will
     * perform the saving of the chapter to the backend.
     *
     * @param  ChapterInterface         $chapter
     * @throws InvalidChapterException when the chapter does not have a lesson.
     */
    public function saveChapter(ChapterInterface $chapter)
    {
        if (null === $chapter->getLesson()) {
            throw new InvalidChapterException('The chapter must have a lesson');
        }

        $event = new ChapterPersistEvent($chapter);
        $this->dispatcher->dispatch(Events::CHAPTER_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveChapter($chapter);

        $event = new ChapterEvent($chapter);
        $this->dispatcher->dispatch(Events::CHAPTER_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a chapter.
     *
     * @abstract
     * @param ChapterInterface $chapter
     */
    abstract protected function doSaveChapter(ChapterInterface $chapter);
}
