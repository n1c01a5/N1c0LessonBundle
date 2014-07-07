<?php

namespace N1c0\LessonBundle;

final class Events
{
    /**
     * The PRE_PERSIST event occurs prior to the persistence backend
     * persisting the Lesson.
     *
     * This event allows you to modify the data in the Lesson prior
     * to persisting occuring. The listener receives a
     * N1c0\LessonBundle\Event\LessonPersistEvent instance.
     *
     * Persisting of the lesson can be aborted by calling
     * $event->abortPersist()
     *
     * @var string
     */
    const LESSON_PRE_PERSIST = 'n1c0_lesson.lesson.pre_persist';

    /**
     * The POST_PERSIST event occurs after the persistence backend
     * persisted the Lesson.
     *
     * This event allows you to notify users or perform other actions
     * that might require the Lesson to be persisted before performing
     * those actions. The listener receives a
     * N1c0\LessonBundle\Event\LessonEvent instance.
     *
     * @var string
     */
    const LESSON_POST_PERSIST = 'n1c0_lesson.lesson.post_persist';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Lesson.
     *
     * The listener receives a N1c0\LessonBundle\Event\LessonEvent
     * instance.
     *
     * @var string
     */
    const LESSON_CREATE = 'n1c0_lesson.lesson.create';

    /**
     * The PRE_PERSIST event occurs prior to the persistence backend
     * persisting the Chapter.
     *
     * This event allows you to modify the data in the Chapter prior
     * to persisting occuring. The listener receives a
     * N1c0\LessonBundle\Event\ChapterPersistEvent instance.
     *
     * Persisting of the chapter can be aborted by calling
     * $event->abortPersist()
     *
     * @var string
     */
    const CHAPTER_PRE_PERSIST = 'n1c0_lesson.chapter.pre_persist';

    /**
     * The POST_PERSIST event occurs after the persistence backend
     * persisted the Chapter.
     *
     * This event allows you to notify users or perform other actions
     * that might require the Argument to be persisted before performing
     * those actions. The listener receives a
     * N1c0\LessonBundle\Event\ChapterEvent instance.
     *
     * @var string
     */
    const CHAPTER_POST_PERSIST = 'n1c0_lesson.chapter.post_persist';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Chapter.
     *
     * The listener receives a N1c0\LessonBundle\Event\ChapterEvent
     * instance.
     *
     * @var string
     */
    const CHAPTER_CREATE = 'n1c0_lesson.chapter.create';

    /**
     * The PRE_PERSIST event occurs prior to the persistence backend
     * persisting the Conclusion.
     *
     * This event allows you to modify the data in the Conclusion prior
     * to persisting occuring. The listener receives a
     * N1c0\LessonBundle\Event\ConclusionPersistEvent instance.
     *
     * Persisting of the conclusion can be aborted by calling
     * $event->abortPersist()
     *
     * @var string
     */
    const CONCLUSION_PRE_PERSIST = 'n1c0_lesson.conclusion.pre_persist';

    /**
     * The POST_PERSIST event occurs after the persistence backend
     * persisted the Conclusion.
     *
     * This event allows you to notify users or perform other actions
     * that might require the Conclusion to be persisted before performing
     * those actions. The listener receives a
     * N1c0\LessonBundle\Event\ConclusionEvent instance.
     *
     * @var string
     */
    const CONCLUSION_POST_PERSIST = 'n1c0_lesson.conclusion.post_persist';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Conclusion.
     *
     * The listener receives a N1c0\LessonBundle\Event\ConclusionEvent
     * instance.
     *
     * @var string
     */
    const CONCLUSION_CREATE = 'n1c0_lesson.conclusion.create';
}
