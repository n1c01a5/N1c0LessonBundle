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
     * persisting the Introduction.
     *
     * This event allows you to modify the data in the Introduction prior
     * to persisting occuring. The listener receives a
     * N1c0\LessonBundle\Event\IntroductionPersistEvent instance.
     *
     * Persisting of the introduction can be aborted by calling
     * $event->abortPersist()
     *
     * @var string
     */
    const INTRODUCTION_PRE_PERSIST = 'n1c0_lesson.introduction.pre_persist';

    /**
     * The POST_PERSIST event occurs after the persistence backend
     * persisted the Argument.
     *
     * This event allows you to notify users or perform other actions
     * that might require the Introduction to be persisted before performing
     * those actions. The listener receives a
     * N1c0\LessonBundle\Event\IntroductionEvent instance.
     *
     * @var string
     */
    const INTRODUCTION_POST_PERSIST = 'n1c0_lesson.introduction.post_persist';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Introduction.
     *
     * The listener receives a N1c0\LessonBundle\Event\IntroductionEvent
     * instance.
     *
     * @var string
     */
    const INTRODUCTION_CREATE = 'n1c0_lesson.introduction.create';

    
    /**
     * The PRE_PERSIST event occurs prior to the persistence backend
     * persisting the Part.
     *
     * This event allows you to modify the data in the Part prior
     * to persisting occuring. The listener receives a
     * N1c0\LessonBundle\Event\PartPersistEvent instance.
     *
     * Persisting of the part can be aborted by calling
     * $event->abortPersist()
     *
     * @var string
     */
    const PART_PRE_PERSIST = 'n1c0_lesson.part.pre_persist';

    /**
     * The POST_PERSIST event occurs after the persistence backend
     * persisted the Part.
     *
     * This event allows you to notify users or perform other actions
     * that might require the Argument to be persisted before performing
     * those actions. The listener receives a
     * N1c0\LessonBundle\Event\PartEvent instance.
     *
     * @var string
     */
    const PART_POST_PERSIST = 'n1c0_lesson.part.post_persist';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Part.
     *
     * The listener receives a N1c0\LessonBundle\Event\PartEvent
     * instance.
     *
     * @var string
     */
    const PART_CREATE = 'n1c0_lesson.part.create';

    /**
     * The PRE_PERSIST event occurs prior to the persistence backend
     * persisting the Argument.
     *
     * This event allows you to modify the data in the Argument prior
     * to persisting occuring. The listener receives a
     * N1c0\LessonBundle\Event\ArgumentPersistEvent instance.
     *
     * Persisting of the argument can be aborted by calling
     * $event->abortPersist()
     *
     * @var string
     */
    const ARGUMENT_PRE_PERSIST = 'n1c0_lesson.argument.pre_persist';

    /**
     * The POST_PERSIST event occurs after the persistence backend
     * persisted the Argument.
     *
     * This event allows you to notify users or perform other actions
     * that might require the Argument to be persisted before performing
     * those actions. The listener receives a
     * N1c0\LessonBundle\Event\ArgumentEvent instance.
     *
     * @var string
     */
    const ARGUMENT_POST_PERSIST = 'n1c0_lesson.argument.post_persist';

    /**
     * The CREATE event occurs when the manager is asked to create
     * a new instance of a Argument.
     *
     * The listener receives a N1c0\LessonBundle\Event\ArgumentEvent
     * instance.
     *
     * @var string
     */
    const ARGUMENT_CREATE = 'n1c0_lesson.argument.create';

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
