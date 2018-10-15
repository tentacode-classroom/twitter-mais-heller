<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\RegistrationType;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */


    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $formRegistration = $this->createForm(RegistrationType::class, $user);
        $formRegistration->handleRequest($request);

        if ($formRegistration->isSubmitted() && $formRegistration->isValid()) {
            $user = $formRegistration->getData();
            
            $plainPassword = $user->getPassword();
            $encryptedPassword = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encryptedPassword);

            $file = $user->getProfilePicture();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            $user->setProfilePicture($fileName);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'registration_success',
                'Vous avez été inscrit(e) !'
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('homepage.html.twig', array(
            'formRegistration' => $formRegistration->createView(),
        ));
    }

}
