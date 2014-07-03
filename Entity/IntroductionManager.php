<?php

namespace N1c0\LessonBundle\Entity;

use Doctrine\ORM\EntityManager;
use N1c0\LessonBundle\Model\IntroductionManager as BaseIntroductionManager;
use N1c0\LessonBundle\Model\LessonInterface;
use N1c0\LessonBundle\Model\IntroductionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ORM IntroductionManager.
 *
 */
class IntroductionManager extends BaseIntroductionManager
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
     * Returns a flat array of introductions of a specific lesson.
     *
     * @param  LessonInterface $lesson
     * @return array           of LessonInterface
     */
    public function findIntroductionsByLesson(LessonInterface $lesson)
    {
        $qb = $this->repository
                ->createQueryBuilder('a')
                ->join('a.lesson', 'd')
                ->where('d.id = :lesson')
                ->setParameter('lesson', $lesson->getId());

        $introductions = $qb
            ->getQuery()
            ->execute();

        return $introductions;
    }

    /**
     * Find one introduction by its ID
     *
     * @param  array           $criteria
     * @return IntroductionInterface
     */
    public function findLessonById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Finds all Introductions.
     *
     * @return array of IntroductionInterface
     */
    public function findAllIntroductions()
    {
        return $this->repository->findAll();
    }

    /**
     * Performs persisting of the introduction. 
     *
     * @param LessonInterface $lesson
     */
    protected function doSaveIntroduction(IntroductionInterface $introduction)
    {
        $this->em->persist($introduction->getLesson());
        $this->em->persist($introduction);
        $this->em->flush();
    }

    /**
     * Returns the fully qualified introduction lesson class name
     *
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }
}
