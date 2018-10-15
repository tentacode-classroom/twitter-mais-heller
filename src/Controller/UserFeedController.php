<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

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
   
    return $this->render('UserFeed/index.html.twig', [
        'user' => $user,
        ]);
    }
}
