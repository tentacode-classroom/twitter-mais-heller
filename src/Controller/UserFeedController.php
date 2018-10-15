<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFeedController extends AbstractController
{
    /**
     * @Route("/user/{userId}", name="user_feed")
     */
    public function index(Request $request, $userId = 3)
    {
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($userId);

        $message = new Message();


        $formMessage = $this->createForm(MessageType::class, $message);
        $formMessage->handleRequest($request);

        if ($formMessage->isSubmitted() && $formMessage->isValid()) {
            $message = $formMessage->getData();

            $message->setPostDate(new \DateTime('NOW'));
            $message->setUser($user);
            $message->setLikes(0);
            $message->setRetweet(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            
            return $this->redirect($request->getUri());
        }
   
    return $this->render('UserFeed/index.html.twig', [
        'user' => $user,
        'formMessage' => $formMessage->createView(),
        ]);
    }



    /**
     * @Route("/user/deletemessage/{messageId}", name="delete_message")
     */
    public function deleteMessage(Message $messageId, UserInterface $user){
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $userId = $user->getId();
        $queryBuilder->select('m')
        ->from(Message::class, 'm')
        ->where('m.id = :id')
        ->setParameter('id', $messageId);
        $query = $queryBuilder->getQuery();
        $message = $query->execute();

        $id = $message[0]->getUser()->getId();

        if($id != $userId){
            return $this->redirect('/');
        }

        $queryBuilder->delete(Message::class, 'm')
           ->where('m.id = :id')
           ->setParameter('id', $messageId);
        
        $query = $queryBuilder->getQuery();
        $query->execute();
        $userId = $user->getId();
        return $this->redirect('/user/'.$userId);

    }
}