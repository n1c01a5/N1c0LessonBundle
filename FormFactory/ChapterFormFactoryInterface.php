<?php

namespace N1c0\LessonBundle\FormFactory;

use Symfony\Component\Form\FormInterface;

/**
 * Chapter form creator
 */
interface ChapterFormFactoryInterface
{
    /**
     * Creates a chapter form
     *
     * @return FormInterface
     */
    public function createForm();
}
