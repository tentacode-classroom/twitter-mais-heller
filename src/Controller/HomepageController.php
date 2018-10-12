<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */


    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $formRegistration = $this->createFormBuilder($user)
            ->add('username', TextType::class, array('label' => 'Nom d\'utilisateur : '))
            ->add('firstName', TextType::class, array('label' => 'Prénom : '))
            ->add('lastName', TextType::class, array('label' => 'Nom de famille : '))
            ->add('email', EmailType::class, array('label' => 'E-mail : '))
            ->add('password', PasswordType::class, array('label' => 'Mot de passe : '))
            ->add('profilePicture', FileType::class, array('label' => 'Photo de profil : '))
            ->add('save', SubmitType::class, array('label' => 'S\'inscrire'))
            ->getForm();

        $formRegistration->handleRequest($request);

        if ($formRegistration->isSubmitted() && $formRegistration->isValid()) {
            $user = $formRegistration->getData();
            
            $plainPassword = $user->getPassword();
            $encryptedPassword = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encryptedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('homepage.html.twig', array(
            'formRegistration' => $formRegistration->createView(),
        ));
    }
}
