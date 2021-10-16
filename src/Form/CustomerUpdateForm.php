<?php

namespace App\Form;

use App\Entity\Customer\Customer;
use App\Form\Components\ContactForm;
use App\Form\Components\FirstLastNameForm;
use App\Form\Components\PassportFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerUpdateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FirstLastNameForm::class, [
                'data_class' => Customer::class,
            ])
            ->add('contacts', ContactForm::class, [
                'data_class' => Customer::class,
            ])
            ->add('passport', PassportFormType::class, [
                'label' => 'Passport data',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Update',
                'attr' => [
                    'class' => 'btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}