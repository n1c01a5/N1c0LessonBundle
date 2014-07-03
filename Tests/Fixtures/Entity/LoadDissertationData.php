<?php

namespace N1c0\LessonBundle\Tests\Fixtures\Entity;

use N1c0\LessonBundle\Entity\Lesson;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadLessonData implements FixtureInterface
{
    static public $lessons = array();

    public function load(ObjectManager $manager)
    {
        $lesson = new Lesson();
        $lesson->setTitle('title');
        $lesson->setBody('body');

        $manager->persist($lesson);
        $manager->flush();

        self::$lessons[] = $lesson;
    }
}
