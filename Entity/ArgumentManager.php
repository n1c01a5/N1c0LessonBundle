<?php

namespace N1c0\LessonBundle\Entity;

use Doctrine\ORM\EntityManager;
use N1c0\LessonBundle\Model\ArgumentManager as BaseArgumentManager;
use N1c0\LessonBundle\Model\ChapterInterface;
use N1c0\LessonBundle\Model\ArgumentInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ORM ArgumentManager.
 *
 */
class ArgumentManager extends BaseArgumentManager
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
     * Returns a flat array of arguments of a specific chapter of the lesson.
     *
     * @param  ChapterInterface $chapter
     * @return array           
     */
    public function findArgumentsByChapter(ChapterInterface $chapter)
    {
        $qb = $this->repository
                ->createQueryBuilder('a')
                ->join('a.chapter', 'p')
                ->where('p.id = :chapter')
                ->setParameter('chapter', $chapter->getId());

        $arguments = $qb
            ->getQuery()
            ->execute();

        return $arguments;
    }

    /**
     * Find one argument by its ID
     *
     * @param  array           $criteria
     * @return ArgumentInterface
     */
    public function findChapterById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Finds all Arguments.
     *
     * @return array of ArgumentInterface
     */
    public function findAllArguments()
    {
        return $this->repository->findAll();
    }

    /**
     * Performs persisting of the argument. 
     *
     * @param ArgumentInterface $argument
     */
    protected function doSaveArgument(ArgumentInterface $argument)
    {
        $this->em->persist($argument->getChapter());
        $this->em->persist($argument);
        $this->em->flush();
    }

    /**
     * Returns the fully qualified argument lesson class name
     *
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }
}
