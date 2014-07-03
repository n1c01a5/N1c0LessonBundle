<?php

namespace N1c0\LessonBundle\FormFactory;

use Symfony\Component\Form\FormInterface;

/**
 * Argument form creator
 */
interface ArgumentFormFactoryInterface
{
    /**
     * Creates a argument form
     *
     * @return FormInterface
     */
    public function createForm();
}
