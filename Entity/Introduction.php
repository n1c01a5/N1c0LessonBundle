<?php

namespace N1c0\LessonBundle\Entity;

use N1c0\LessonBundle\Model\Introduction as AbstractIntroduction;

use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Hateoas\Relation(
 *     name = "self",
 *     href = @Hateoas\Route(
 *         "api_1_get_lesson_introduction",
 *         parameters = { "id" = "expr(object.getLesson().getId())", "introductionId" = "expr(object.getId())" },
 *         absolute = true
 *     )
 * )
 */
abstract class Introduction extends AbstractIntroduction
{

}
