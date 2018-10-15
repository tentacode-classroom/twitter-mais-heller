<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('profileName', TextType::class, array('label' => 'Nom d\'utilisateur : '))
        ->add('firstName', TextType::class, array('label' => 'Prénom : '))
        ->add('lastName', TextType::class, array('label' => 'Nom de famille : '))
        ->add('birthday', BirthdayType::class, array('label' => 'Date de naissance : '))
        ->add('email', EmailType::class, array('label' => 'E-mail : '))
        ->add('password', PasswordType::class, array('label' => 'Mot de passe : '))
        ->add('profilePicture', FileType::class, array('label' => 'Photo de profil : '))
        ->add('save', SubmitType::class, array('label' => 'S\'inscrire'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
