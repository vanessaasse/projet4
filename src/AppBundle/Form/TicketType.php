<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array(
            'label' => 'Nom :'))
            ->add('firstname', TextType::class, array('label' => 'Prénom :'))
            ->add('country', CountryType::class, array('label' => 'Pays :'))
            ->add('birthday', BirthdayType::class, array('label' => 'Date de naissance :'))
            ->add('discount', CheckboxType::class, array(
                'label' => 'Tarif réduit de 10 euros 
            - Tarif accordé sous certaines conditions (étudiant, employé du musée, d’un service du Ministère de la Culture, militaire…)',
                'required' => false
            ))
            ->add('save', SubmitType::class, array('label' => 'Validez'));
    }



    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ticket::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ticket';
    }


}
