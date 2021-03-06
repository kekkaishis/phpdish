<?php

namespace PHPDish\Bundle\ForumBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use PHPDish\Bundle\CoreBundle\Controller\RestController;
use PHPDish\Bundle\ForumBundle\Event\Events;
use PHPDish\Bundle\ForumBundle\Event\TopicRepliedEvent;
use PHPDish\Bundle\ForumBundle\Form\Type\TopicReplyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TopicReplyController extends RestController
{
    use ManagerTrait;

    use \PHPDish\Bundle\UserBundle\Controller\ManagerTrait;

    /**
     * 删除回复.
     *
     * @Route("/replies/{id}", name="topic_reply_delete", requirements={"id": "\d+"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $manager = $this->getReplyManager();
        $reply = $manager->findReplyById($id);
        if (!$reply) {
            throw $this->createNotFoundException();
        }
        $this->denyAccessUnlessGranted('edit', $reply);
        $manager->blockReply($reply);

        return $this->handleView($this->view([
            'result' => true,
        ]));
    }

    /**
     * @Route("/users/{username}/replies", name="user_replies")
     *
     * @param string  $username
     * @param Request $request
     *
     * @return Response
     */
    public function getUserRepliesAction($username, Request $request)
    {
        $user = $this->getUserManager()->findUserByName($username);
        $criteria = Criteria::create()->where(Criteria::expr()->eq('enabled', true))
            ->orderBy(['createdAt' => 'desc']);

        $replies = $this->getReplyManager()
            ->findUserReplies($user, $request->query->getInt('page', 1), null, $criteria);

        return $this->render('PHPDishWebBundle:Topic:user_replies.html.twig', [
            'user' => $user,
            'replies' => $replies,
        ]);
    }
}
