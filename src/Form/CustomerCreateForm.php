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
use Symfony\Component\Validator\Constraints\NotNull;

class CustomerCreateForm extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FirstLastNameForm::class, [
                'data_class'    => Customer::class,
                'constraints'   => [
                    new NotNull(),
                ],
            ])
            ->add('contacts', ContactForm::class, [
                'data_class'    => Customer::class,
            ])
            ->add('passport', PassportFormType::class, [
                'label'         => 'Passport data',
                'constraints'   => [
                    new NotNull(),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label'         => 'Create',
                'attr'          => [
                    'class' => 'btn-success',
                ],
            ])
        ;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}