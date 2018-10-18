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
    public function index(
        Request $request,
        \Swift_Mailer $mailer,
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage
    ) {
        $user = new User();

        $formRegistration = $this->createForm(RegistrationType::class, $user);
        $formRegistration->handleRequest($request);

        if ($formRegistration->isSubmitted() && $formRegistration->isValid()) {
            $user = $formRegistration->getData();
            
            // SECURITY : crypter le mot de passe dans la base de données
            $plainPassword = $user->getPassword();
            $encryptedPassword = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encryptedPassword);

            // renommer photo de profil pour qu'il n'y en pas avec un nom similaire
            $file = $user->getProfilePicture();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            $user->setProfilePicture($fileName);

            // renommer bannière pour qu'il n'y en pas avec un nom similaire
            $file2 = $user->getBannerPicture();
            $fileName2 = md5(uniqid()).'.'.$file2->guessExtension();
            $file2->move($this->getParameter('upload_directory'), $fileName2);
            $user->setBannerPicture($fileName2);

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
            ->setFrom('louise.baulan@gmail.com')
            ->setTo($userMail)
            ->setBody('Vous avez été inscrit(e) à Heller ! Bienvenue dans notre communauté !');

            $mailer->send($message);
        }
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
            'formRegistration' => $formRegistration->createView(),
        ));
    }
}
