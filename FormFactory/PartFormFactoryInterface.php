<?php

namespace N1c0\LessonBundle\FormFactory;

use Symfony\Component\Form\FormInterface;

/**
 * Part form creator
 */
interface PartFormFactoryInterface
{
    /**
     * Creates a part form
     *
     * @return FormInterface
     */
    public function createForm();
}
