<?php

namespace App\Form;

use App\Entity\Voucher\Voucher;
use App\Entity\Voucher\VoucherAdditionalInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoucherUpdateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active?',
                'label_attr' => [
                    'class' => 'checkbox-inline',
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price in dollars',
                'label_attr' => [
                    'class' => 'checkbox-inline',
                ],
            ])
            ->add('fromPlace', TextType::class, [
                'label' => 'From place',
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('toPlace', TextType::class, [
                'label' => 'To place',
                'attr'  => [
                    'class' => 'form-control',
                ],
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
            'data_class' => Voucher::class,
        ]);
    }
}