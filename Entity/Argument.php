<?php

namespace N1c0\LessonBundle\Entity;

use N1c0\LessonBundle\Model\Argument as AbstractArgument;

use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Hateoas\Relation(
 *     name = "self",
 *     href = @Hateoas\Route(
 *         "api_1_get_lesson_part_argument",
 *         parameters = { "id" = "expr(object.getPart().getLesson().getId())", "partId" = "expr(object.getPart().getId())" ,"argumentId" = "expr(object.getId())" },
 *         absolute = true
 *     )
 * )
 */
abstract class Argument extends AbstractArgument
{

}
