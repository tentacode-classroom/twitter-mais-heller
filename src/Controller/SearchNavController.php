<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchNavController extends AbstractController
{

    public function index()
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchNavController',
        ]);
    }
}
