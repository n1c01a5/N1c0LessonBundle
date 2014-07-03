<?php

namespace N1c0\LessonBundle\FormFactory;

use Symfony\Component\Form\FormInterface;

/**
 * Lesson form creator
 */
interface LessonFormFactoryInterface
{
    /**
     * Creates a lesson form
     *
     * @return FormInterface
     */
    public function createForm();
}
