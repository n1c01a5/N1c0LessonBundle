<?php

namespace N1c0\LessonBundle\Entity;

use N1c0\LessonBundle\Model\Lesson as AbstractLesson;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Serializer\XmlRoot("lesson")
 *
 * @Hateoas\Relation(
 *     name = "self",
 *     href = @Hateoas\Route(
 *         "api_1_get_lesson",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute = true
 *     )
 * )
 */
abstract class Lesson extends AbstractLesson
{

}
