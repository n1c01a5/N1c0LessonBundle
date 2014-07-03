<?php

namespace N1c0\LessonBundle\FormFactory;

use Symfony\Component\Form\FormInterface;

/**
 * Introduction form creator
 */
interface IntroductionFormFactoryInterface
{
    /**
     * Creates a introduction form
     *
     * @return FormInterface
     */
    public function createForm();
}
