<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Friend;
use App\Entity\Likes;
use App\Entity\Retweet;
use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserFeedController extends AbstractController
{
    /**
     * @Route("/user/{userId}", name="user_feed")
     */
    public function index(Request $request, TokenStorageInterface $tokenStorage, $userId)
    {

        //User qui visite
        $loggedUser=$tokenStorage->getToken()->getUser();

        //User de la page visitée
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            
            return $this->redirect($request->getUri());
        }

           $lastUsers = $this->getDoctrine()
           ->getRepository(User::class)
           ->findLasts();
    
   
        return $this->render('UserFeed/index.html.twig', [
        'user' => $user,
        'loggedUser' => $loggedUser,
        'formMessage' => $formMessage->createView(),
        'lastUsers' => $lastUsers,
        ]);
    }
    /**
     * @Route("/user/deletemessage/{messageId}", name="delete_message")
     */
    public function deleteMessage(Message $messageId, UserInterface $user, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder1 = $entityManager->createQueryBuilder();
        $queryBuilder2 = $entityManager->createQueryBuilder();
        $queryBuilder3 = $entityManager->createQueryBuilder();
        
        $userId = $user->getId();
        $queryBuilder->select('m')
        ->from(Message::class, 'm')
        ->where('m.id = :id')
        ->setParameter('id', $messageId);
        $query = $queryBuilder->getQuery();
        $message = $query->execute();
        $id = $message[0]->getUser()->getId();
        $role = $user->getRoles()[0];
        
        if ($id != $userId && $role == "ROLE_USER") {
            return $this->redirect('/');
        }
        $queryBuilder1->delete(Likes::class, 'l')
        ->where('l.messageLiked = :id')
        ->setParameter('id', $message[0]->getId());
        $query = $queryBuilder1->getQuery();
        $query->execute();

        $queryBuilder2->delete(Retweet::class, 'r')
        ->where('r.messageRetweeted = :id')
        ->setParameter('id', $message[0]->getId());
        $query = $queryBuilder2->getQuery();
        $query->execute();

        $queryBuilder3->delete(Message::class, 'm')
           ->where('m.id = :id')
           ->setParameter('id', $message[0]->getId());
        
        $query = $queryBuilder3->getQuery();
        $query->execute();
        
        $previousUrl = $request->server->get('HTTP_REFERER');

        return $this->redirect($previousUrl);
    }

    /**
     * @Route("/user/follow/{userId}", name="follow_user")
     */
    public function followUser(User $userId, UserInterface $user, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $followerId = $user->getId();

        $friend = new Friend();
        $friend->setFollower($user);
        $friend->setFollowing($userId);
        $entityManager->persist($friend);
        $entityManager->flush();
        $previousUrl = $request->server->get('HTTP_REFERER');
        return $this->redirect($previousUrl);
    }

        
    /**
     * @Route("/user/unfollow/{userId}", name="unfollow_user")
     */
    public function unfollowUser(User $userId, UserInterface $user, Request $request)
    {
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
           $previousUrl = $request->server->get('HTTP_REFERER');
   
           return $this->redirect($previousUrl);
    }

    /**
    * @Route("/user/like/{messageId}", name="like_message")
    */
    public function likeMessage(Message $messageId, UserInterface $loggedUser, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $message = $this->getDoctrine()->getRepository(Message::class) ->find($messageId);

        $like = new Likes();
        $like->setLiker($loggedUser);
        $like->setMessageLiked($message);
        $entityManager->persist($like);
        $entityManager->flush();
        
        $previousUrl = $request->server->get('HTTP_REFERER');

        return $this->redirect($previousUrl);
    }


        /**
    * @Route("/user/unlike/{messageId}", name="unlike_message")
    */
    public function unlikeMessage(Message $messageId, UserInterface $loggedUser, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->delete(Likes::class, 'l')
        ->andWhere('l.liker = :liker')
        ->setParameter(':liker', $loggedUser)
        ->andWhere('l.messageLiked = :messageLiked')
        ->setParameter(':messageLiked', $messageId);
           $query = $queryBuilder->getQuery();
           $query->execute();
        
        $previousUrl = $request->server->get('HTTP_REFERER');

        return $this->redirect($previousUrl);
    }


        /**
    * @Route("/user/retweet/{messageId}", name="retweet_message")
    */
    public function retweetMessage(Message $messageId, UserInterface $loggedUser, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $message = $this->getDoctrine()->getRepository(Message::class) ->find($messageId);

        $retweet = new Retweet();
        $retweet->setRetweeter($loggedUser);
        $retweet->setMessageRetweeted($message);
        $entityManager->persist($retweet);
        $entityManager->flush();
        
        $previousUrl = $request->server->get('HTTP_REFERER');

        return $this->redirect($previousUrl);
    }


        /**
    * @Route("/user/unretweet/{messageId}", name="unretweet_message")
    */
    public function unretweetMessage(Message $messageId, UserInterface $loggedUser, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->delete(Retweet::class, 'l')
        ->andWhere('l.retweeter = :retweeter')
        ->setParameter(':retweeter', $loggedUser)
        ->andWhere('l.messageRetweeted = :messageRetweeted')
        ->setParameter(':messageRetweeted', $messageId);
           $query = $queryBuilder->getQuery();
           $query->execute();
        
        $previousUrl = $request->server->get('HTTP_REFERER');

        return $this->redirect($previousUrl);
    }
}
