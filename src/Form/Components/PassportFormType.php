<?php

namespace App\Form\Components;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serial', NumberType::class, [
                'label' => 'Serial',
                'row_attr' => $options['fields_row_attr']['serial'],
            ])
            ->add('number', NumberType::class, [
                'label' => 'Number',
                'row_attr' => $options['fields_row_attr']['number'],
            ])
            ->add('office', TextType::class, [
                'label' => 'Issuing office address',
                'row_attr' => $options['fields_row_attr']['office'],
            ])
            ->add('home', TextType::class, [
                'label' => 'Home address',
                'row_attr' => $options['fields_row_attr']['home'],
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Birthday date',
                'years' => $this->getYears(),
                'row_attr' => $options['fields_row_attr']['office'],
            ]);
    }

    protected function getYears(): array
    {
        return \array_map(function ($index) {
            return (new \DateTime())->format('Y') - $index;
        }, \array_keys(\array_fill(0, 100, 0)));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label_attr' => [
                'class' => 'border-bottom border-dark mb-3 h6'
            ],
            'attr' => [
                'class' => 'row justify-content-center'
            ],
            'fields_row_attr' => [
                'serial' => [
                    'class' => 'col-6',
                ],
                'number' => [
                    'class' => 'col-6',
                ],
                'office' => [
                    'class' => 'col-6 mt-3'
                ],
                'home' => [
                    'class' => 'col-6 mt-3'
                ],
                'birthday' => [
                    'class' => 'm-auto'
                ]
            ],
        ]);
    }
}