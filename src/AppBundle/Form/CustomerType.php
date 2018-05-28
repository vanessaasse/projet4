<?php

namespace AppBundle\Form;

use AppBundle\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array('label' => 'Nom :'))
            ->add('firstname', TextType::class, array('label' => 'Prénom :'))
            ->add('email', EmailType::class, array('label' => 'Email :'))
            ->add('adress', TextType::class, array('label' => 'Adresse :'))
            ->add('postCode', TextType::class, array('label' => 'Code Postal'))
            ->add('city', TextType::class, array('label' => 'Ville'))
            ->add('country', CountryType::class, array('label' => 'Pays'))
            ->add('save', SubmitType::class, array('label' => 'Validez'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Customer::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_customer';
    }


}
