<?php

namespace N1c0\LessonBundle\Entity;

use N1c0\LessonBundle\Model\Conclusion as AbstractConclusion;

use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Hateoas\Relation(
 *     name = "self",
 *     href = @Hateoas\Route(
 *         "api_1_get_lesson_conclusion",
 *         parameters = { "id" = "expr(object.getLesson().getId())", "conclusionId" = "expr(object.getId())" },
 *         absolute = true
 *     )
 * )
 */
abstract class Conclusion extends AbstractConclusion
{

}
