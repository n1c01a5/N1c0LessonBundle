<?php

namespace N1c0\LessonBundle\Entity;

use Doctrine\ORM\EntityManager;
use N1c0\LessonBundle\Model\PartManager as BasePartManager;
use N1c0\LessonBundle\Model\LessonInterface;
use N1c0\LessonBundle\Model\PartInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ORM PartManager.
 *
 */
class PartManager extends BasePartManager
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
     * Returns a flat array of parts of a specific lesson.
     *
     * @param  LessonInterface $lesson
     * @return array           of LessonInterface
     */
    public function findPartsByLesson(LessonInterface $lesson)
    {
        $qb = $this->repository
                ->createQueryBuilder('a')
                ->join('a.lesson', 'd')
                ->where('d.id = :lesson')
                ->setParameter('lesson', $lesson->getId());

        $parts = $qb
            ->getQuery()
            ->execute();

        return $parts;
    }

    /**
     * Find one part by its ID
     *
     * @param  array           $criteria
     * @return PartInterface
     */
    public function findLessonById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Finds all Parts.
     *
     * @return array of PartInterface
     */
    public function findAllParts()
    {
        return $this->repository->findAll();
    }

    /**
     * Performs persisting of the part. 
     *
     * @param LessonInterface $lesson
     */
    protected function doSavePart(PartInterface $part)
    {
        $this->em->persist($part->getLesson());
        $this->em->persist($part);
        $this->em->flush();
    }

    /**
     * Returns the fully qualified part lesson class name
     *
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }
}
