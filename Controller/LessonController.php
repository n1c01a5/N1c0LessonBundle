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

class LessonController extends FOSRestController
{
    /**
     * List all lessons.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing lessons.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="100", description="How many lessons to return.")
     *
     * @Annotations\View(
     *  templateVar="lessons"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getLessonsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('n1c0_lesson.manager.lesson')->all($limit, $offset);
    }

    /**
     * Get single Lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Lesson for a given id",
     *   output = "N1c0\LessonBundle\Entity\Lesson",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the lesson is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="lesson")
     *
     * @param int     $id      the lesson id
     *
     * @return array
     *
     * @throws NotFoundHttpException when lesson not exist
     */
    public function getLessonAction($id)
    {
        $lesson = $this->getOr404($id);

        return $lesson;
    }

    /**
     * Presents the form to use to create a new lesson.
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
     * @return FormTypeInterface
     */
    public function newLessonAction()
    {
        return $form = $this->container->get('n1c0_lesson.form_factory.lesson')->createForm();
    }

    /**
     * Edits a lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Lesson:editLesson.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param int     $id      the lesson id
     * @return FormTypeInterface
     */
    public function editLessonAction($id)
    {
        $lesson = $this->getOr404($id);
        $form = $this->container->get('n1c0_lesson.form_factory.lesson')->createForm();
        $form->setData($lesson);

        return array(
            'form' => $form,
            'id'=>$id
        );
    }

    /**
     * Create a Lesson from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new lesson from the submitted data.",
     *   input = "N1c0\LessonBundle\Form\LessonType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Lesson:newLesson.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postLessonAction(Request $request)
    {
        try {
            $lessonManager = $this->container->get('n1c0_lesson.manager.lesson');
            $lesson = $lessonManager->createLesson();

            $form = $this->container->get('n1c0_lesson.form_factory.lesson')->createForm();
            $form->setData($lesson);

            if ('POST' === $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $lessonManager->saveLesson($lesson);

                    $routeOptions = array(
                        'id' => $form->getData()->getId(),
                        '_format' => $request->get('_format')
                    );

                    // Add a method onCreateLessonSuccess(FormInterface $form)
                    return $this->routeRedirectView('api_1_get_lesson', $routeOptions, Codes::HTTP_CREATED);
                }
            }
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }

        // Add a method onCreateLessonError(FormInterface $form)
        return new Response(sprintf("Error of the lesson id '%s'.", $form->getData()->getId()), Codes::HTTP_BAD_REQUEST);

    }

    /**
     * Update existing lesson from the submitted data or create a new lesson at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Updates a lesson.",
     *   input = "N1c0\DemoBundle\Form\LessonType",
     *   statusCodes = {
     *     200 = "Returned when the Lesson is updated",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Lesson:editLesson.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the lesson id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when lesson not exist
     */
    public function putLessonAction(Request $request, $id)
    {
        try {
            $lesson = $this->getOr404($id);

            $form = $this->container->get('n1c0_lesson.form_factory.lesson')->createForm();
            $form->setData($lesson);
            $form->bind($request);

            if ($form->isValid()) {
                $lessonManager = $this->container->get('n1c0_lesson.manager.lesson');
                if($lessonManager->saveLesson($lesson) !== false) {
                    $routeOptions = array(
                        'id' => $lesson->getId(),
                        '_format' => $request->get('_format')
                    );

                    return $this->routeRedirectView('api_1_get_lesson', $routeOptions, Codes::HTTP_OK); // Must return 200 for ajax request
                }
            }
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }

        // Add a method onCreateLessonError(FormInterface $form)
        return new Response(sprintf("Error of the lesson id '%s'.", $form->getData()->getId()), Codes::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing lesson from the submitted data or create a new lesson at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Updates a lesson.",
     *   input = "N1c0\DemoBundle\Form\LessonType",
     *   statusCodes = {
     *     200 = "Returned when the Lesson is updated",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Lesson:editLesson.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the lesson id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when lesson not exist
     */
    public function patchLessonAction(Request $request, $id)
    {
        try {
            $lesson = $this->getOr404($id);

            $form = $this->container->get('n1c0_lesson.form_factory.lesson')->createForm();
            $form->setData($lesson);
            $form->bind($request);

            if ($form->isValid()) {
                $lessonManager = $this->container->get('n1c0_lesson.manager.lesson');
                if($lessonManager->saveLesson($lesson) !== false) {
                    $routeOptions = array(
                        'id' => $lesson->getId(),
                        '_format' => $request->get('_format')
                    );

                    return $this->routeRedirectView('api_1_get_lesson', $routeOptions, Codes::HTTP_OK); // Must return 200 for ajax request
                }
            }
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }

        // Add a method onCreateLessonError(FormInterface $form)
        return new Response(sprintf("Error of the lesson id '%s'.", $form->getData()->getId()), Codes::HTTP_BAD_REQUEST);
    }

    /**
     * Get thread for the lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a comment thread",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @Annotations\View(templateVar="thread")
     *
     * @param int     $id      the lesson uuid
     *
     * @return array
     */
    public function getLessonThreadAction($id)
    {
        return $this->container->get('n1c0_lesson.comment.lesson_comment.default')->getThread($id);
    }

    /**
     * Removes a lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request         the request object
     * @param int     $id              the lesson id
     *
     * @return RouteRedirectView
     */
    public function deleteLessonAction(Request $request, $id)
    {
        $lessonManager = $this->container->get('n1c0_lesson.manager.lesson');
        $lesson = $this->getOr404($id);

        $lessonManager->removeLesson($lesson);

        return $this->routeRedirectView('api_1_get_lessons', array(), Codes::HTTP_NO_CONTENT);
    }

    /**
     * Fetch a Lesson or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return LessonInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $lesson;
    }

    /**
     * Get download for the lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a download lesson",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @Annotations\View(templateVar="lesson")
     *
     * @param int     $id      the lesson uuid
     *
     * @return array
     * @throws NotFoundHttpException when lesson not exist
     */
    public function getLessonDownloadAction($id)
    {
        if (!($lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
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
            'formats' => $formats,
            'id' => $id
        );
    }

    /**
     * Convert the lesson in pdf format.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Convert the lesson",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @param int     $id      the lesson uuid
     * @param string  $format  the format to convert lesson
     *
     * @return null
     * @throws NotFoundHttpException when lesson not exist
     */
    public function getLessonConvertAction($id, $format)
    {
        if (!($lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        $lessonConvert = $this->container->get('n1c0_lesson.lesson.download')->getConvert($id, $format);

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
        $filename = $lesson->getTitle().'.'.$ext;
        $fh = fopen('./uploads/'.$filename, "w+");
        if ($fh==false) {
            die("Oops! Unable to create file");
        }
        fputs($fh, $lessonConvert);

        return $this->redirect($_SERVER['SCRIPT_NAME'].'/../uploads/'.$filename);
    }

    /**
     * Get logs of a single Lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets lofs of a Lesson for a given id",
     *   output = "Gedmo\Loggable\Entity\LogEntry",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the lesson is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="lesson")
     *
     * @param int     $id      the lesson id
     *
     * @return array
     *
     * @throws NotFoundHttpException when lesson not exist
     */
    public function logsLessonAction($id)
    {
        $lesson = $this->getOr404($id);
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry'); // we use default log entry class
        $entity = $em->find('Entity\Lesson', $lesson->getId());
        $logs = $repo->getLogEntries($entity);

        return $logs;
    }
}
