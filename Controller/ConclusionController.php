<?php

namespace N1c0\LessonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use N1c0\LessonBundle\Exception\InvalidFormException;
use N1c0\LessonBundle\Form\LessonType;
use N1c0\LessonBundle\Model\LessonInterface;
use N1c0\LessonBundle\Form\ConclusionType;
use N1c0\LessonBundle\Model\ConclusionInterface;

class ConclusionController extends FOSRestController
{
    /**
     * Get single Conclusion.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Conclusion for a given id",
     *   output = "N1c0\LessonBundle\Entity\Conclusion",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the conclusion or the lesson is not found"
     *   }
     * )
     *
     *
     * @Annotations\View(templateVar="conclusion")
     *
     * @param int                   $id                   the lesson id
     * @param int                   $conclusionId           the conclusion id
     *
     * @return array
     *
     * @throws NotFoundHttpException when conclusion not exist
     */
    public function getConclusionAction($id, $conclusionId)
    {
        $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
        if (!$lesson) {
            throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
        }

        return $this->getOr404($conclusionId);
    }

    /**
     * Get the conclusions of a lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing conclusions.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many conclusions to return.")
     *
     * @Annotations\View(
     *  templateVar="conclusions"
     * )
     *
     * @param int                   $id           the lesson id
     *
     * @return array
     */
    public function getConclusionsAction($id)
    {
        $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
        if (!$lesson) {
            throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
        }

        return $this->container->get('n1c0_lesson.manager.conclusion')->findConclusionsByLesson($lesson);
    }

    /**
     * Presents the form to use to create a new conclusion.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @param int                   $id           the lesson id
     *
     * @return FormTypeInterface
     */
    public function newConclusionAction($id)
    {
        $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
        if (!$lesson) {
            throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
        }

        $conclusion = $this->container->get('n1c0_lesson.manager.conclusion')->createConclusion($lesson);

        $form = $this->container->get('n1c0_lesson.form_factory.conclusion')->createForm();
        $form->setData($conclusion);

        return array(
            'form' => $form,
            'id' => $id
        );
    }

    /**
     * Edits an conclusion.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Conclusion:editConclusion.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param int     $id      the lesson id
     * @param int     $conclusionId           the conclusion id
     *
     * @return FormTypeInterface
     */
    public function editConclusionAction($id, $conclusionId)
    {
        $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
        if (!$lesson) {
            throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
        }
        $conclusion = $this->getOr404($conclusionId);
        $form = $this->container->get('n1c0_lesson.form_factory.conclusion')->createForm();
        $form->setData($conclusion);

        return array(
            'form'         => $form,
            'id'           =>$id,
            'conclusionId' => $conclusion->getId()
        );
    }

    /**
     * Creates a new Conclusion for the Lesson from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new conclusion for the lesson from the submitted data.",
     *   input = "N1c0\LessonBundle\Form\ConclusionType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Conclusion:newConclusion.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param string  $id      The id of the lesson
     *
     * @return FormTypeInterface|View
     */
    public function postConclusionAction(Request $request, $id)
    {
        try {
            $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
            if (!$lesson) {
                throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
            }

            $conclusionManager = $this->container->get('n1c0_lesson.manager.conclusion');
            $conclusion = $conclusionManager->createConclusion($lesson);

            $form = $this->container->get('n1c0_lesson.form_factory.conclusion')->createForm();
            $form->setData($conclusion);

            if ('POST' === $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $conclusionManager->saveConclusion($conclusion);

                    $routeOptions = array(
                        'id' => $id,
                        'conclusionId' => $form->getData()->getId(),
                        '_format' => $request->get('_format')
                    );

                    $response['success'] = true;

                    $request = $this->container->get('request');
                    $isAjax = $request->isXmlHttpRequest();

                    if($isAjax == false) {
                        // Add a method onCreateConclusionSuccess(FormInterface $form)
                        return $this->routeRedirectView('api_1_get_lesson_conclusion', $routeOptions, Codes::HTTP_CREATED);
                    }
                } else {
                    $response['success'] = false;
                }
                return new JsonResponse( $response );
            }
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing conclusion from the submitted data or create a new conclusion at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "N1c0\DemoBundle\Form\ConclusionType",
     *   statusCodes = {
     *     201 = "Returned when the Conclusion is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Conclusion:editConclusion.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request         the request object
     * @param string  $id              the id of the lesson
     * @param int     $conclusionId      the conclusion id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when conclusion not exist
     */
    public function putConclusionAction(Request $request, $id, $conclusionId)
    {
        try {
            $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
            if (!$lesson) {
                throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
            }

            $conclusion = $this->getOr404($conclusionId);

            $form = $this->container->get('n1c0_lesson.form_factory.conclusion')->createForm();
            $form->setData($conclusion);
            $form->bind($request);

            if ($form->isValid()) {
                $conclusionManager = $this->container->get('n1c0_lesson.manager.conclusion');
                if ($conclusionManager->saveConclusion($conclusion) !== false) {
                    $routeOptions = array(
                        'id' => $lesson->getId(),
                        '_format' => $request->get('_format')
                    );

                    return $this->routeRedirectView('api_1_get_lesson', $routeOptions, Codes::HTTP_OK);
                }
            }
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }

        // Add a method onCreateConclusionError(FormInterface $form)
        return new Response(sprintf("Error of the conclusion id '%s'.", $form->getData()->getId()), Codes::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing conclusion for a lesson from the submitted data or create a new conclusion at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "N1c0\DemoBundle\Form\ConclusionType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Conclusion:editLessonConclusion.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request         the request object
     * @param string  $id              the id of the lesson
     * @param int     $conclusionId      the conclusion id

     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when conclusion not exist
     */
    public function patchConclusionAction(Request $request, $id, $conclusionId)
    {
        try {
            $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
            if (!$lesson) {
                throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
            }

            $conclusion = $this->getOr404($conclusionId);

            $form = $this->container->get('n1c0_lesson.form_factory.conclusion')->createForm();
            $form->setData($conclusion);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $conclusionManager = $this->container->get('n1c0_lesson.manager.conclusion');
                if ($conclusionManager->saveConclusion($conclusion) !== false) {
                    $routeOptions = array(
                        'id' => $lesson->getId(),
                        '_format' => $request->get('_format')
                    );

                    return $this->routeRedirectView('api_1_get_lesson', $routeOptions, Codes::HTTP_CREATED);
                }
            }
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Get thread for an conclusion.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a conclusion thread",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @Annotations\View(templateVar="thread")
     *
     * @param int     $id               the lesson id
     * @param int     $conclusionId       the conclusion id
     *
     * @return array
     */
    public function getConclusionThreadAction($id, $conclusionId)
    {
        return $this->container->get('n1c0_lesson.comment.lesson_comment.default')->getThread($conclusionId);
    }

    /**
     * Removes an conclusion of the lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request         the request object
     * @param int     $id              the lesson id of the conclusion
     * @param int     $conclusionId    the conclusion id
     *
     * @return RouteRedirectView
     */
    public function deleteConclusionAction(Request $request, $id, $conclusionId)
    {
        $conclusionManager = $this->container->get('n1c0_lesson.manager.conclusion');
        $conclusion = $this->getOr404($conclusionId);

        $conclusionManager->removeConclusion($conclusion);

        return $this->routeRedirectView('api_1_get_lesson', array('id' => $id), Codes::HTTP_NO_CONTENT);
    }

    /**
     * Fetch a Conclusion or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return ConclusionInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($conclusion = $this->container->get('n1c0_lesson.manager.conclusion')->findConclusionById($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $conclusion;
    }

    /**
     * Get download for the conclusion of the lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a download conclusion",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @Annotations\View(templateVar="conclusion")
     *
     * @param int     $id              the lesson uuid
     * @param int     $conclusionId      the conclusion uuid
     *
     * @return array
     * @throws NotFoundHttpException when lesson not exist
     * @throws NotFoundHttpException when conclusion not exist
     */
    public function getConclusionDownloadAction($id, $conclusionId)
    {
        if (!($lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id))) {
            throw new NotFoundHttpException(sprintf('The resource lesson \'%s\' was not found.',$id));
        }

        if (!($conclusion = $this->container->get('n1c0_lesson.manager.conclusion')->findConclusionById($conclusionId))) {
            throw new NotFoundHttpException(sprintf('The resource conclusion \'%s\' was not found.', $conclusionId));
        }

        $formats = array(
            "native",
            "json",
            "docx",
            "odt",
            "epub",
            "epub3",
            "fb2",
            "html",
            "html5",
            "slidy",
            "dzslides",
            "docbook",
            "opendocument",
            "latex",
            "beamer",
            "context",
            "texinfo",
            "markdown",
            "pdf",
            "plain",
            "rst",
            "mediawiki",
            "textile",
            "rtf",
            "org",
            "asciidoc"
        );

        return array(
            'formats'    => $formats,
            'conclusion'   => $conclusion
        );
    }

    /**
     * Convert the conclusion in pdf format.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Convert the conclusion",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @param int     $id              the lesson uuid
     * @param int     $conclusionId      the conclusion uuid
     * @param string  $format          the format to convert lesson
     *
     * @return null
     * @throws NotFoundHttpException when lesson not exist
     * @throws NotFoundHttpException when conclusion not exist
     */
    public function getConclusionConvertAction($id, $conclusionId, $format)
    {
        if (!($lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id))) {
            throw new NotFoundHttpException(sprintf('The resource lesson \'%s\' was not found.',$id));
        }

        if (!($conclusion = $this->container->get('n1c0_lesson.manager.conclusion')->findConclusionById($conclusionId))) {
            throw new NotFoundHttpException(sprintf('The resource conclusion \'%s\' was not found.',$conclusionId));
        }

        $conclusionConvert = $this->container->get('n1c0_lesson.conclusion.download')->getConvert($conclusionId, $format);

        switch ($format) {
            case "native":
                $ext = "";
            break;
            case "s5":
                $ext = "html";
            break;
            case "slidy":
                $ext = "html";
            break;
            case "slideous":
                $ext = "html";
            break;
            case "dzslides":
                $ext = "html";
            break;
            case "latex":
                $ext = "tex";
            break;
            case "context":
                $ext = "tex";
            break;
            case "beamer":
                $ext = "pdf";
            break;
            case "rst":
                $ext = "text";
            break;
            case "docbook":
                $ext = "db";
            break;
            case "man":
                $ext = "";
            break;
            case "asciidoc":
                $ext = "txt";
            break;
            case "markdown":
                $ext = "md";
            break;
            case "epub3":
                $ext = "epub";
            break;
            default:
                $ext = $format;
        }

        if ($ext == "") {$ext = "txt";}
        $filename = $conclusion->getTitle().'.'.$ext;
        $fh = fopen('./uploads/'.$filename, "w+");
        if($fh==false) {
            die("Oops! Unable to create file");
        }
        fputs($fh, $conclusionConvert);

        return $this->redirect($_SERVER['SCRIPT_NAME'].'/../uploads/'.$filename);
    }

}
