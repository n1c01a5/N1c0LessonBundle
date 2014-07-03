<?php

namespace N1c0\LessonBundle\Model;

/**
 * Interface to be implemented by part managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to comments should happen through this interface.
 */
interface PartManagerInterface
{
    /**
     * Get a list of Parts.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * @param  string          $id
     * @return PartInterface
     */
    public function findPartById($id);

    /**
     * Returns a flat array of parts with the specified lesson.
     *
     * @param  LessonInterface $lesson
     * @return array           of PartInterface
     */
    public function findPartsByLesson(LessonInterface $lesson);

    /**
     * Returns an empty part instance
     *
     * @return Part
     */
    public function createPart(LessonInterface $lesson);

    /**
     * Saves a part
     *
     * @param  PartInterface         $part
     */
    public function savePart(PartInterface $part);
}
