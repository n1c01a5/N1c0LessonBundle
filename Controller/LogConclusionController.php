<?php

namespace N1c0\LessonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class LogConclusionController extends FOSRestController
{
    /**
     * Gets logs.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets logs for a given id",
     *   output = "Gedmo\Loggable\Entity\LogEntry", 
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the entity is not found"
     *   }
     * )
     *
     *
     * @Annotations\View(
     *  template = "N1c0LessonBundle:Conclusion:getLogs.html.twig",
     *  templateVar="logs"   
     * )
     *
     * @param int                   $id                   the lesson id
     * @param int                   $conclusionId           the conclusionentity id
     *
     * @return array
     *
     * @throws NotFoundHttpException when entity not exist
     */
    public function getLogsAction($id, $conclusionId)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');

        if ($conclusion = $this->container->get('n1c0_lesson.manager.conclusion')->findConclusionById($conclusionId)) {
            $entity = $em->find('Bundle\LessonBundle\Entity\Conclusion', $conclusion->getId());
        }
        else {
            throw new NotFoundHttpException(sprintf('Entity with identifier of "%s" does not exist', $conclusionId));
        }

        $logs = $repo->getLogEntries($entity);
        
        $c = count($logs);

        // if $c == 0 $logsEntity = ???
        
        for($i = 1; $i <= $c; $i++) {
            $repo->revert($entity, $i);
            $logsEntity[$i]['title'] = $entity->getTitle();
            $logsEntity[$i]['body'] = $entity->getBody(); 
            $logsEntity[$i]['author'] = $entity->getAuthor(); 
            $logsEntity[$i]['date'] = $entity->getCreatedAt()->format('d/m/Y à H:m'); 
            $logsEntity[$i]['commitTitle'] = $entity->getCommitTitle(); 
            $logsEntity[$i]['commitBody'] = $entity->getCommitBody(); 
            $logsEntity[$i]['lessonId'] = $entity->getLesson()->getId(); 
        }
        
        return $logsEntity;
    }
}
