<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Friend;
use App\Entity\Like;
use App\Form\RegistrationType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(TokenStorageInterface $tokenStorage)
    {
        $loggedUser=$tokenStorage->getToken()->getUser();
        if ($loggedUser != "anon.") {
            $loggedUser->followers = $this->getDoctrine()
            ->getRepository(Friend::class)
            ->findFollowers($loggedUser);

            $loggedUser->followings = $this->getDoctrine()
            ->getRepository(Friend::class)
            ->findFollowings($loggedUser);
        }
        
        return $this->render('homepage.html.twig', array(
            'user' => $loggedUser,
        ));
    }
}
