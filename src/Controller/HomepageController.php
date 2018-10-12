<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('homepage.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    public function new(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array('label' => 'Nom d\'utilisateur : '))
            ->add('firstName', TextType::class, array('label' => 'Prénom : '))
            ->add('lastName', TextType::class, array('label' => 'Nom de famille : '))
            ->add('email', EmailType::class, array('label' => 'E-mail : '))
            ->add('password', PasswordType::class, array('label' => 'Mot de passe : '))
            ->add('profilePicture', FileType::class, array('label' => 'Photo de profil : '))
            ->add('save', SubmitType::class, array('label' => 'S\'inscrire'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            
            $plainPassword = $user->getPassword();
            $encryptedPassword = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encryptedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }
    }
}
