<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array(
            'label' => 'Nom*',
            'required' => true
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom*',
                'required' => true
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email*',
                'required' => true
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'Message*',
                'required' => true,
                'constraints' => []
            ));
    }

    public function getName()
    {
        return 'Contact';
    }



}