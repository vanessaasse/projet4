<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;



class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array(
            'label' => 'label.lastname',
            'required' => true,
            'constraints' => array(
                    new NotBlank(array('message' => 'constraint.contactType_lastname_notBlank'))
            )))
            ->add('firstname', TextType::class, array(
                'label' => 'label.firstname',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'constraint.contactType_firstname_notBlank'))
            )))
            ->add('email', EmailType::class, array(
                'label' => 'label.email',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'constraint.contactType_email_notBlank')),
                    new Email(array('strict' => true, 'message' => "constraint.contactType_email_valide"))
            )))
            ->add('message', TextareaType::class, array(
                'label' => 'label.message',
                'required' => true,
                'constraints' => array(
                    new NotBlank(array('message' => 'constraint.contactType_message_notBlank'))

            )));
    }

    public function getName()
    {
        return 'Contact';
    }



}