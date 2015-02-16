<?php

namespace N1c0\LessonBundle\Model;

/**
 * Interface to be implemented by chapter managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to comments should happen through this interface.
 */
interface ChapterManagerInterface
{
    /**
     * Get a list of Chapters.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit, $offset);

    /**
     * @param  string          $id
     * @return ChapterInterface
     */
    public function findChapterById($id);

    /**
     * Returns a flat array of chapters with the specified lesson.
     *
     * @param  LessonInterface $lesson
     * @return array           of ChapterInterface
     */
    public function findChaptersByLesson(LessonInterface $lesson);

    /**
     * Returns an empty chapter instance
     *
     * @return Chapter
     */
    public function createChapter(LessonInterface $lesson);

    /**
     * Saves a chapter
     *
     * @param  ChapterInterface         $chapter
     */
    public function saveChapter(ChapterInterface $chapter);
}
