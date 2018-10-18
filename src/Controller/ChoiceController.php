<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChoiceController extends AbstractController
{
    /**
     * @Route("/", name="choice")
     */
    public function index()
    {
        return $this->render('choice/index.html.twig', [
            'controller_name' => 'ChoiceController',
        ]);
    }
}
