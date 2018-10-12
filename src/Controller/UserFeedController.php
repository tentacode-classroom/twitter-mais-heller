<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserFeedController extends AbstractController
{
    /**
     * @Route("/user/", name="user_feed")
     */
    public function index()
    {
        return $this->render('UserFeed/index.html.twig', [
            'controller_name' => 'UserFeedController',
        ]);
    }
}
