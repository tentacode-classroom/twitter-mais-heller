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


    public function index(Request $request, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder)
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

            // affichage du message flash pour confirmer l'inscription
            $this->addFlash(
                'registration_success',
                'Vous avez été inscrit(e) !'
            );

            // envoi d'un mail pour confirmer l'inscription
            $userMail = $user->getEmail();
            $message = (new \Swift_Message('Inscription Heller'))
            ->setFrom('send@example.com')
            ->setTo($userMail)
            ->setBody('Vous avez été inscrit(e) à Heller ! Bienvenue dans notre communauté !');
            
            $mailer->send($message);

            return $this->render('homepage.html.twig', array(
                'formRegistration' => $formRegistration->createView(),
            ));
        }
    }

}
