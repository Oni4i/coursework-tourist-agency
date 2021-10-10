<?php

namespace App\Form;

use App\Entity\Voucher\Voucher;
use App\Entity\Voucher\VoucherAdditionalInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoucherCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('additional', ChoiceType::class, [
                'label' => 'Additional',
                'choices' => \array_combine(
                    \array_values(VoucherAdditionalInterface::ADDITIONS_FOR_FORMS),
                    VoucherAdditionalInterface::ADDITIONS_FOR_FORMS
                ),
                'multiple'  => true,
                'expanded' => true,
                'required' => true,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active?',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price in dollars',
            ])
            ->add('fromPlace', TextType::class, [
                'label' => 'From place',
            ])
            ->add('toPlace', TextType::class, [
                'label' => 'To place',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create',
                'attr' => [
                    'class' => 'btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Voucher::class,
        ]);
    }
}