<?php

namespace N1c0\LessonBundle\Tests\Handler;

use N1c0\LessonBundle\Handler\LessonHandler;
use N1c0\LessonBundle\Model\LessonInterface;
use N1c0\LessonBundle\Entity\Lesson;

class LessonHandlerTest extends \PHPUnit_Framework_TestCase
{
    const PAGE_CLASS = 'n1c0\LessonBundle\Tests\Handler\DummyLesson';

    /** @var LessonHandler */
    protected $lessonHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::PAGE_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::PAGE_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::PAGE_CLASS));
    }


    public function testGet()
    {
        $id = 1;
        $lesson = $this->getLesson();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($lesson));

        $this->lessonHandler = $this->createLessonHandler($this->om, static::PAGE_CLASS,  $this->formFactory);

        $this->lessonHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $lessons = $this->getLessons(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($lessons));

        $this->lessonHandler = $this->createLessonHandler($this->om, static::PAGE_CLASS,  $this->formFactory);

        $all = $this->lessonHandler->all($limit, $offset);

        $this->assertEquals($lessons, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $lesson = $this->getLesson();
        $lesson->setTitle($title);
        $lesson->setBody($body);

        $form = $this->getMock('n1c0\LessonBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($lesson));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->lessonHandler = $this->createLessonHandler($this->om, static::PAGE_CLASS,  $this->formFactory);
        $lessonObject = $this->lessonHandler->post($parameters);

        $this->assertEquals($lessonObject, $lesson);
    }

    /**
     * @expectedException n1c0\LessonBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $lesson = $this->getLesson();
        $lesson->setTitle($title);
        $lesson->setBody($body);

        $form = $this->getMock('n1c0\LessonBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->lessonHandler = $this->createLessonHandler($this->om, static::PAGE_CLASS,  $this->formFactory);
        $this->lessonHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $lesson = $this->getLesson();
        $lesson->setTitle($title);
        $lesson->setBody($body);

        $form = $this->getMock('n1c0\LessonBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($lesson));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->lessonHandler = $this->createLessonHandler($this->om, static::PAGE_CLASS,  $this->formFactory);
        $lessonObject = $this->lessonHandler->put($lesson, $parameters);

        $this->assertEquals($lessonObject, $lesson);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('body' => $body);

        $lesson = $this->getLesson();
        $lesson->setTitle($title);
        $lesson->setBody($body);

        $form = $this->getMock('n1c0\LessonBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($lesson));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->lessonHandler = $this->createLessonHandler($this->om, static::PAGE_CLASS,  $this->formFactory);
        $lessonObject = $this->lessonHandler->patch($lesson, $parameters);

        $this->assertEquals($lessonObject, $lesson);
    }


    protected function createLessonHandler($objectManager, $lessonClass, $formFactory)
    {
        return new LessonHandler($objectManager, $lessonClass, $formFactory);
    }

    protected function getLesson()
    {
        $lessonClass = static::PAGE_CLASS;

        return new $lessonClass();
    }

    protected function getLessons($maxLessons = 5)
    {
        $lessons = array();
        for($i = 0; $i < $maxLessons; $i++) {
            $lessons[] = $this->getLesson();
        }

        return $lessons;
    }
}

class DummyLesson extends Lesson
{
}
