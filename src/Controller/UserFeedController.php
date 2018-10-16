<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Friend;
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

        $user->followers = $this->getDoctrine()
        ->getRepository(Friend::class)
        ->findFollowers($user);

        $user->followings = $this->getDoctrine()
        ->getRepository(Friend::class)
        ->findFollowings($user);

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

    /**
     * @Route("/user/follow/{userId}", name="follow_user")
     */
    public function followUser(User $userId, UserInterface $user){
        $entityManager = $this->getDoctrine()->getManager();
        $followerId = $user->getId();

        $friend = new Friend();
        $friend->setFollower($user);
        $friend->setFollowing($userId);
        $entityManager->persist($friend);
        $entityManager->flush();
        $userId=$userId->getId();
        return $this->redirect('/user/'.$userId);
    }

        
    /**
     * @Route("/user/unfollow/{userId}", name="unfollow_user")
     */
    public function unfollowUser(User $userId, UserInterface $user){
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $followerId = $user->getId();

        $queryBuilder->delete(Friend::class, 'f')
        ->andWhere('f.follower = :follower')
        ->setParameter(':follower', $followerId)    
        ->andWhere('f.following = :following') 
        ->setParameter(':following', $userId);     
           $query = $queryBuilder->getQuery();
           $query->execute();
        $userId=$userId->getId();
        return $this->redirect('/user/'.$userId);
    }

}