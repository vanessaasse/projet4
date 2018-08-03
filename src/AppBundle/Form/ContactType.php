<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array(
            'label' => 'Nom*',
            'required' => true,
            'constraints' => array(
                    new NotBlank(array('message' => 'Vous devez saisir votre nom de famille.'))
            )))
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom*',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Vous devez saisir votre prénom.'))
            )))
            ->add('email', EmailType::class, array(
                'label' => 'Email*',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Vous devez saisir votre email.'))
            ))) // TODO mettre strict=true + message email valide comme dans l'entité customer
            ->add('message', TextareaType::class, array(
                'label' => 'Message*',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'Vous devez saisir votre message.'))

            )));
    }

    public function getName()
    {
        return 'Contact';
    }



}