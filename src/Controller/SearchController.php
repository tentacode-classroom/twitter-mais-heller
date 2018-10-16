<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request)
    {
        // $users = new User();

        $formSearch = $this->createForm(SearchUserType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $usersearch = $formSearch->getData();
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findUsers($usersearch['profileName']);

            return $this->render('search/index.html.twig', array(
                'users' => $users,
                'formSearch' => $formSearch->createView(),
            ));
        } else {
            return $this->render('search/index.html.twig', array(
                'formSearch' => $formSearch->createView(),
            ));
        }
    }
}
