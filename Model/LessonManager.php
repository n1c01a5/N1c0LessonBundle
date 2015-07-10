<?php

namespace N1c0\LessonBundle\Model;

use N1c0\LessonBundle\Events;
use N1c0\LessonBundle\Event\LessonEvent;
use N1c0\LessonBundle\Event\LessonPersistEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Abstract Lesson Manager implementation which can be used as base class for your
 * concrete manager.
 */
abstract class LessonManager implements LessonManagerInterface
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
     * Get a list of Lessons.
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
     * @return LessonInterface
     */
    public function findLessonById($id)
    {
        return $this->findLessonBy(array('id' => $id));
    }

    /**
     * Creates an empty element lesson instance
     *
     * @return Lesson
     */
    public function createLesson($id = null)
    {
        $class = $this->getClass();
        $lesson = new $class;

        if (null !== $id) {
            $lesson->setId($id);
        }

        $event = new LessonEvent($lesson);
        $this->dispatcher->dispatch(Events::LESSON_CREATE, $event);

        return $lesson;
    }

    /**
     * Persists a lesson.
     *
     * @param LessonInterface $lesson
     */
    public function saveLesson(LessonInterface $lesson)
    {
        $event = new LessonPersistEvent($lesson);
        $this->dispatcher->dispatch(Events::LESSON_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveLesson($lesson);

        $event = new LessonEvent($lesson);
        $this->dispatcher->dispatch(Events::LESSON_POST_PERSIST, $event);

        return true;
    }

    /**
     * Removes an lesson.
     *
     * @param LessonInterface $lesson
     */
    public function removeLesson(LessonInterface $lesson)
    {
        $this->doRemoveLesson($lesson);

        return true;
    }

    /**
     * Performs the persistence of the Lesson.
     *
     * @abstract
     * @param LessonInterface $lesson
     */
    abstract protected function doSaveLesson(LessonInterface $lesson);

    /**
     * Removes an lesson of the Dissertation.
     *
     * @abstract
     * @param LessonInterface $lesson
     */
    abstract protected function doRemoveLesson(LessonInterface $lesson);
}
