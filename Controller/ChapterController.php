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
use N1c0\LessonBundle\Form\ChapterType;
use N1c0\LessonBundle\Model\ChapterInterface;

class ChapterController extends FOSRestController
{
    /**
     * Get single Chapter.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Chapter for a given id",
     *   output = "N1c0\LessonBundle\Entity\Chapter",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the chapter or the lesson is not found"
     *   }
     * )
     *
     *
     * @Annotations\View(templateVar="chapter")
     *
     * @param int                   $id                   the lesson id
     * @param int                   $chapterId           the chapter id
     *
     * @return array
     *
     * @throws NotFoundHttpException when chapter not exist
     */
    public function getChapterAction($id, $chapterId)
    {
        $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
        if (!$lesson) {
            throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
        }

        return $this->getOr404($chapterId);
    }

    /**
     * Get the chapters of a lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing chapters.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many chapters to return.")
     *
     * @Annotations\View(
     *  templateVar="chapters"
     * )
     *
     * @param int                   $id           the lesson id
     *
     * @return array
     */
    public function getChaptersAction($id)
    {
        $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
        if (!$lesson) {
            throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
        }

        return $this->container->get('n1c0_lesson.manager.chapter')->findChaptersByLesson($lesson);
    }

    /**
     * Presents the form to use to create a new chapter.
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
    public function newChapterAction($id)
    {
        $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
        if (!$lesson) {
            throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
        }

        $chapter = $this->container->get('n1c0_lesson.manager.chapter')->createChapter($lesson);

        $form = $this->container->get('n1c0_lesson.form_factory.chapter')->createForm();
        $form->setData($chapter);

        return array(
            'form' => $form,
            'id' => $id
        );
    }

    /**
     * Edits an chapter.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Chapter:editChapter.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param int     $id      the lesson id
     * @param int     $chapterId           the chapter id
     *
     * @return FormTypeInterface
     */
    public function editChapterAction($id, $chapterId)
    {
        $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
        if (!$lesson) {
            throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
        }
        $chapter = $this->getOr404($chapterId);
        $form = $this->container->get('n1c0_lesson.form_factory.chapter')->createForm();
        $form->setData($chapter);

        return array(
            'form' => $form,
            'id'=>$id,
            'chapterId' => $chapter->getId()
        );
    }

    /**
     * Creates a new Chapter for the Lesson from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new chapter for the lesson from the submitted data.",
     *   input = "N1c0\LessonBundle\Form\ChapterType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Chapter:newChapter.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param string  $id      The id of the lesson
     *
     * @return FormTypeInterface|View
     */
    public function postChapterAction(Request $request, $id)
    {
        try {
            $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
            if (!$lesson) {
                throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
            }

            $chapterManager = $this->container->get('n1c0_lesson.manager.chapter');
            $chapter = $chapterManager->createChapter($lesson);

            $form = $this->container->get('n1c0_lesson.form_factory.chapter')->createForm();
            $form->setData($chapter);

            if ('POST' === $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $chapterManager->saveChapter($chapter);

                    $routeOptions = array(
                        'id' => $id,
                        'chapterId' => $form->getData()->getId(),
                        '_format' => $request->get('_format')
                    );

                    $response['success'] = true;

                    $request = $this->container->get('request');
                    $isAjax = $request->isXmlHttpRequest();

                    if($isAjax == false) {
                        // Add a method onCreateChapterSuccess(FormInterface $form)
                        return $this->routeRedirectView('api_1_get_lesson_chapter', $routeOptions, Codes::HTTP_CREATED);
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
     * Update existing chapter from the submitted data or create a new chapter at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "N1c0\DemoBundle\Form\ChapterType",
     *   statusCodes = {
     *     201 = "Returned when the Chapter is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Chapter:editChapter.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request         the request object
     * @param string  $id              the id of the lesson
     * @param int     $chapterId      the chapter id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when chapter not exist
     */
    public function putChapterAction(Request $request, $id, $chapterId)
    {
        try {
            $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
            if (!$lesson) {
                throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
            }

            $chapter = $this->getOr404($chapterId);

            $form = $this->container->get('n1c0_lesson.form_factory.chapter')->createForm();
            $form->setData($chapter);
            $form->bind($request);

            if ($form->isValid()) {
                $chapterManager = $this->container->get('n1c0_lesson.manager.chapter');
                if ($chapterManager->saveChapter($chapter) !== false) {
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

        // Add a method onCreateChapterError(FormInterface $form)
        return new Response(sprintf("Error of the chapter id '%s'.", $form->getData()->getId()), Codes::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing chapter for a lesson from the submitted data or create a new chapter at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "N1c0\DemoBundle\Form\ChapterType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Chapter:editLessonChapter.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request         the request object
     * @param string  $id              the id of the lesson
     * @param int     $chapterId      the chapter id

     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when chapter not exist
     */
    public function patchChapterAction(Request $request, $id, $chapterId)
    {
        try {
            $lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id);
            if (!$lesson) {
                throw new NotFoundHttpException(sprintf('Lesson with identifier of "%s" does not exist', $id));
            }

            $chapter = $this->getOr404($chapterId);

            $form = $this->container->get('n1c0_lesson.form_factory.chapter')->createForm();
            $form->setData($chapter);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $chapterManager = $this->container->get('n1c0_lesson.manager.chapter');
                if ($chapterManager->saveChapter($chapter) !== false) {
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
     * Get thread for an chapter.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a chapter thread",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @Annotations\View(templateVar="thread")
     *
     * @param int     $id               the lesson id
     * @param int     $chapterId       the chapter id
     *
     * @return array
     */
    public function getChapterThreadAction($id, $chapterId)
    {
        return $this->container->get('n1c0_lesson.comment.lesson_comment.default')->getThread($chapterId);
    }

    /**
     * Removes an chapter of the lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request         the request object
     * @param int     $id              the lesson id of the chapter
     * @param int     $chapterId      the chapter id
     *
     * @return RouteRedirectView
     */
    public function deleteChapterAction(Request $request, $id, $chapterId)
    {
        $chapterManager = $this->container->get('n1c0_lesson.manager.chapter');
        $chapter = $this->getOr404($chapterId);

        $chapterManager->removeChapter($chapter);

        return $this->routeRedirectView('api_1_get_lesson', array('id' => $id), Codes::HTTP_NO_CONTENT);
    }

    /**
     * Fetch a Chapter or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return ChapterInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($chapter = $this->container->get('n1c0_lesson.manager.chapter')->findChapterById($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $chapter;
    }

    /**
     * Get download for the chapter of the lesson.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a download chapter",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @Annotations\View(templateVar="chapter")
     *
     * @param int     $id              the lesson uuid
     * @param int     $chapterId      the chapter uuid
     *
     * @return array
     * @throws NotFoundHttpException when lesson not exist
     * @throws NotFoundHttpException when chapter not exist
     */
    public function getChapterDownloadAction($id, $chapterId)
    {
        if (!($lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id))) {
            throw new NotFoundHttpException(sprintf('The resource lesson \'%s\' was not found.',$id));
        }

        if (!($chapter = $this->container->get('n1c0_lesson.manager.chapter')->findChapterById($chapterId))) {
            throw new NotFoundHttpException(sprintf('The resource chapter \'%s\' was not found.', $chapterId));
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
            'chapter'   => $chapter
        );
    }

    /**
     * Convert the chapter in pdf format.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Convert the chapter",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @param int     $id              the lesson uuid
     * @param int     $chapterId      the chapter uuid
     * @param string  $format          the format to convert lesson
     *
     * @return null
     * @throws NotFoundHttpException when lesson not exist
     * @throws NotFoundHttpException when chapter not exist
     */
    public function getChapterConvertAction($id, $chapterId, $format)
    {
        if (!($lesson = $this->container->get('n1c0_lesson.manager.lesson')->findLessonById($id))) {
            throw new NotFoundHttpException(sprintf('The resource lesson \'%s\' was not found.',$id));
        }

        if (!($chapter = $this->container->get('n1c0_lesson.manager.chapter')->findChapterById($chapterId))) {
            throw new NotFoundHttpException(sprintf('The resource chapter \'%s\' was not found.',$chapterId));
        }

        $chapterConvert = $this->container->get('n1c0_lesson.chapter.download')->getConvert($chapterId, $format);

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
        $filename = $chapter->getTitle().'.'.$ext;
        $fh = fopen('./uploads/'.$filename, "w+");
        if($fh==false) {
            die("Oops! Unable to create file");
        }
        fputs($fh, $chapterConvert);

        return $this->redirect($_SERVER['SCRIPT_NAME'].'/../uploads/'.$filename);
    }

}
