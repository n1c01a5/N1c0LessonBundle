<?php

namespace N1c0\LessonBundle\Model;

use N1c0\LessonBundle\Events;
use N1c0\LessonBundle\Event\ArgumentEvent;
use N1c0\LessonBundle\Event\ArgumentPersistEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use InvalidArgumentException;

/**
 * Abstract Argument Manager implementation which can be used as base class for your
 * concrete manager.
 */
abstract class ArgumentManager implements ArgumentManagerInterface
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
     * Get a list of Arguments.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * @param  string          $id
     * @return ArgumentInterface
     */
    public function findArgumentById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Returns an empty argument instance
     *
     * @return Argument
     */
    public function createArgument(ChapterInterface $chapter)
    {
        $class = $this->getClass();
        $argument = new $class;

        $argument->setChapter($chapter);

        $event = new ArgumentEvent($argument);
        $this->dispatcher->dispatch(Events::ARGUMENT_CREATE, $event);

        return $argument;
    }

    /**
     * Saves a argument to the persistence backend used. Each backend
     * must implement the abstract doSaveArgument method which will
     * perform the saving of the argument to the backend.
     *
     * @param  ArgumentInterface         $argument
     * @throws InvalidArgumentException when the argument does not have a chapter of the lesson.
     */
    public function saveArgument(ArgumentInterface $argument)
    {
        if (null === $argument->getChapter()) {
            throw new InvalidArgumentException('The argument must have a chapter');
        }

        $event = new ArgumentPersistEvent($argument);
        $this->dispatcher->dispatch(Events::ARGUMENT_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveArgument($argument);

        $event = new ArgumentEvent($argument);
        $this->dispatcher->dispatch(Events::ARGUMENT_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a argument.
     *
     * @abstract
     * @param ArgumentInterface $argument
     */
    abstract protected function doSaveArgument(ArgumentInterface $argument);
}
