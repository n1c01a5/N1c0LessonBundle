<?php

namespace N1c0\LessonBundle\Model;

/**
 * Interface to be implemented by element lesson managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to element lesson should happen through this interface.
 */
interface LessonManagerInterface
{
    /**
     * Get a list of Lessons.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit, $offset);

    /**
     * @param  string          $id
     * @return LessonInterface
     */
    public function findLessonById($id);

    /**
     * Finds one element lesson by the given criteria
     *
     * @param  array           $criteria
     * @return LessonInterface
     */
    public function findLessonBy(array $criteria);

    /**
     * Finds lessons by the given criteria
     *
     * @param array $criteria
     *
     * @return array of LessonInterface
     */
    public function findLessonsBy(array $criteria);

    /**
     * Finds all lessons.
     *
     * @return array of LessonInterface
     */
    public function findAllLessons();

    /**
     * Creates an empty element lesson instance
     *
     * @param  bool   $id
     * @return Lesson
     */
    public function createLesson($id = null);

    /**
     * Saves a lesson
     *
     * @param LessonInterface $lesson
     */
    public function saveLesson(LessonInterface $lesson);

    /**
     * Checks if the lesson was already persisted before, or if it's a new one.
     *
     * @param LessonInterface $lesson
     *
     * @return boolean True, if it's a new lesson
     */
    public function isNewLesson(LessonInterface $lesson);

    /**
     * Removes a lesson
     *
     * @param LessonInterface $lesson
     */
    public function removeLesson(LessonInterface $lesson);

    /**
     * Returns the element lesson fully qualified class name
     *
     * @return string
     */
    public function getClass();
}
