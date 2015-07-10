<?php

namespace N1c0\LessonBundle\Entity;

use Doctrine\ORM\EntityManager;
use N1c0\LessonBundle\Model\LessonManager as BaseLessonManager;
use N1c0\LessonBundle\Model\LessonInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ORM LessonManager.
 *
 */
class LessonManager extends BaseLessonManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Doctrine\ORM\EntityManager                                 $em
     * @param string                                                      $class
     */
    public function __construct(EventDispatcherInterface $dispatcher, EntityManager $em, $class)
    {
        parent::__construct($dispatcher);

        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;
    }

    /**
     * Finds one element lesson by the given criteria
     *
     * @param  array           $criteria
     * @return LessonInterface
     */
    public function findLessonBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findLessonsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Finds all lessons.
     *
     * @return array of LessonInterface
     */
    public function findAllLessons()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNewLesson(LessonInterface $lesson)
    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($lesson);
    }

    /**
     * Removes an argument of the dissertation
     *
     * @param LessonInterface $argument
     */
    protected function doRemoveLesson(LessonInterface $lesson)
    {
        $this->em->remove($lesson);
        $this->em->flush();
    }

    /**
     * Saves a lesson
     *
     * @param LessonInterface $lesson
     */
    protected function doSaveLesson(LessonInterface $lesson)
    {
        $this->em->persist($lesson);
        $this->em->flush();
    }

    /**
     * Returns the fully qualified element lesson class name
     *
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }
}
