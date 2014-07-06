<?php

namespace N1c0\LessonBundle\Entity;

use N1c0\LessonBundle\Model\Chapter as AbstractChapter;

use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Hateoas\Relation(
 *     name = "self",
 *     href = @Hateoas\Route(
 *         "api_1_get_lesson_chapter",
 *         parameters = { "id" = "expr(object.getLesson().getId())", "chapterId" = "expr(object.getId())" },
 *         absolute = true
 *     )
 * )
 */
abstract class Chapter extends AbstractChapter
{

}
