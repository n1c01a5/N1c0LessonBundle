<?php

namespace N1c0\LessonBundle\Entity;

use Doctrine\ORM\EntityManager;
use N1c0\LessonBundle\Model\ChapterManager as BaseChapterManager;
use N1c0\LessonBundle\Model\LessonInterface;
use N1c0\LessonBundle\Model\ChapterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default ORM ChapterManager.
 *
 */
class ChapterManager extends BaseChapterManager
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
     * Returns a flat array of chapters of a specific lesson.
     *
     * @param  LessonInterface $lesson
     * @return array           of LessonInterface
     */
    public function findChaptersByLesson(LessonInterface $lesson)
    {
        $qb = $this->repository
                ->createQueryBuilder('a')
                ->join('a.lesson', 'd')
                ->where('d.id = :lesson')
                ->setParameter('lesson', $lesson->getId());

        $chapters = $qb
            ->getQuery()
            ->execute();

        return $chapters;
    }

    /**
     * Find one chapter by its ID
     *
     * @param  array           $criteria
     * @return ChapterInterface
     */
    public function findLessonById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Finds all Chapters.
     *
     * @return array of ChapterInterface
     */
    public function findAllChapters()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNewChapter(ChapterInterface $chapter)
    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($chapter);
    }

    /**
     * Performs persisting of the chapter.
     *
     * @param ChapterInterface $chapter
     */
    protected function doSaveChapter(ChapterInterface $chapter)
    {
        $this->em->persist($chapter->getLesson());
        $this->em->persist($chapter);
        $this->em->flush();
    }

    /**
     * Removes an chapter of the dissertation
     *
     * @param ChapterInterface $chapter
     */
    protected function doRemoveChapter(ChapterInterface $chapter)
    {
        $this->em->remove($chapter);
        $this->em->flush();
    }

    /**
     * Returns the fully qualified chapter lesson class name
     *
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }
}
