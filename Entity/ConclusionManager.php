<?php

namespace N1c0\LessonBundle\Entity;

use Doctrine\ORM\EntityManager;
use N1c0\LessonBundle\Model\ConclusionManager as BaseConclusionManager;
use N1c0\LessonBundle\Model\LessonInterface;
use N1c0\LessonBundle\Model\ConclusionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ORM ConclusionManager.
 *
 */
class ConclusionManager extends BaseConclusionManager
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
     * Returns a flat array of conclusions of a specific lesson.
     *
     * @param  LessonInterface $lesson
     * @return array           of LessonInterface
     */
    public function findConclusionsByLesson(LessonInterface $lesson)
    {
        $qb = $this->repository
                ->createQueryBuilder('a')
                ->join('a.lesson', 'd')
                ->where('d.id = :lesson')
                ->setParameter('lesson', $lesson->getId());

        $conclusions = $qb
            ->getQuery()
            ->execute();

        return $conclusions;
    }

    /**
     * Find one conclusion by its ID
     *
     * @param  array           $criteria
     * @return ConclusionInterface
     */
    public function findLessonById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Finds all Conclusions.
     *
     * @return array of ConclusionInterface
     */
    public function findAllConclusions()
    {
        return $this->repository->findAll();
    }

    /**
     * Performs persisting of the conclusion. 
     *
     * @param LessonInterface $lesson
     */
    protected function doSaveConclusion(ConclusionInterface $conclusion)
    {
        $this->em->persist($conclusion->getLesson());
        $this->em->persist($conclusion);
        $this->em->flush();
    }

    /**
     * Returns the fully qualified conclusion lesson class name
     *
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }
}
