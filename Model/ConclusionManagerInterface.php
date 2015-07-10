<?php

namespace N1c0\LessonBundle\Model;

/**
 * Interface to be implemented by conclusion managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to comments should happen through this interface.
 */
interface ConclusionManagerInterface
{
    /**
     * Get a list of Conclusions.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit, $offset);

    /**
     * @param  string          $id
     * @return ConclusionInterface
     */
    public function findConclusionById($id);

    /**
     * Returns a flat array of conclusions with the specified lesson.
     *
     * @param  LessonInterface $lesson
     * @return array           of ConclusionInterface
     */
    public function findConclusionsByLesson(LessonInterface $lesson);

    /**
     * Returns an empty conclusion instance
     *
     * @return Conclusion
     */
    public function createConclusion(LessonInterface $lesson);

    /**
     * Saves a conclusion
     *
     * @param  ConclusionInterface         $conclusion
     */
    public function saveConclusion(ConclusionInterface $conclusion);

    /**
     * Removes a conclusion
     *
     * @param ConclusionInterface $conclusion
     */
    public function removeConclusion(ConclusionInterface $conclusion);

    /**
     * Checks if the conclusion was already persisted before, or if it's a new one.
     *
     * @param ConclusionInterface $conclusion
     *
     * @return boolean True, if it's a new conclusion
     */
    public function isNewConclusion(ConclusionInterface $conclusion);
}
