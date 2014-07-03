<?php

namespace N1c0\LessonBundle\Entity;

use N1c0\LessonBundle\Model\Part as AbstractPart;

use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Hateoas\Relation(
 *     name = "self",
 *     href = @Hateoas\Route(
 *         "api_1_get_lesson_part",
 *         parameters = { "id" = "expr(object.getLesson().getId())", "partId" = "expr(object.getId())" },
 *         absolute = true
 *     )
 * )
 */
abstract class Part extends AbstractPart
{

}
