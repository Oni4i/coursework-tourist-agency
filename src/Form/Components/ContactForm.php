<?php

namespace App\Form\Components;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['include_phone']) {
            $builder->add('phone', TextType::class, [
                'label' => 'Phone',
                'attr' => [
                    'class' => 'phone-type',
                ],
                'row_attr' => $options['fields_row_attr']['phone'],
            ]);
        }

        if ($options['include_email']) {
            $builder->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'email-type',
                ],
                'row_attr' => $options['fields_row_attr']['email'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'inherit_data' => true,
            'include_phone' => true,
            'include_email' => true,
            'attr' => [
                'class' => 'row'
            ],
            'fields_row_attr' => [
                'phone' => [
                    'class' => 'col-6',
                ],
                'email' => [
                    'class' => 'col-6',
                ]
            ],
            'label' => false,
        ]);
    }
}