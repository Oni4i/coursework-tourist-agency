<?php

namespace App\Form\Components;

use App\Entity\Customer\Passport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class PassportFormType extends AbstractType
{
    const YEARS_DIFFERENCE = 100;

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serial', NumberType::class, [
                'label'     => 'Serial',
                'row_attr'  => $options['fields_row_attr']['serial'],
                'constraints'   => [
                    new NotNull(),
                    new Length([
                        'min' => 4,
                        'max' => 4,
                    ])
                ],
            ])
            ->add('number', NumberType::class, [
                'label'     => 'Number',
                'row_attr'  => $options['fields_row_attr']['number'],
                'constraints'   => [
                    new NotNull(),
                    new Length([
                        'min' => 6,
                        'max' => 6,
                    ])
                ],
            ])
            ->add('office', TextType::class, [
                'label'     => 'Issuing office address',
                'row_attr'  => $options['fields_row_attr']['office'],
                'constraints'   => [
                    new NotNull(),
                ],
            ])
            ->add('home', TextType::class, [
                'label'     => 'Home address',
                'row_attr'  => $options['fields_row_attr']['home'],
                'constraints'   => [
                    new NotNull(),
                ],
            ])
            ->add('birthday', DateType::class, [
                'label'     => 'Birthday date',
                'years'     => $this->getYears(),
                'row_attr'  => $options['fields_row_attr']['office'],
                'constraints'   => [
                    new NotNull(),
                ],
            ])
        ;
    }

    /**
     * Array between current year and current year - YEARS_DIFFERENCE
     *
     * @return array
     */
    protected function getYears(): array
    {
        //Fill array with 0 YEARS_DIFFERENCE times
        $years = \array_fill(0, self::YEARS_DIFFERENCE, 0);
        //Fill array with his indexes
        $years = \array_keys($years);

        //Subtraction the index from the current year
        return \array_map(function ($index) {
            return (new \DateTime())->format('Y') - $index;
        }, $years);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Passport::class,
            'label_attr' => [
                'class' => 'border-bottom border-dark mb-3 h6',
            ],
            'attr' => [
                'class' => 'row justify-content-center',
            ],
            'fields_row_attr' => [
                'serial' => [
                    'class' => 'col-6',
                ],
                'number' => [
                    'class' => 'col-6',
                ],
                'office' => [
                    'class' => 'col-6 mt-3',
                ],
                'home' => [
                    'class' => 'col-6 mt-3',
                ],
                'birthday' => [
                    'class' => 'm-auto',
                ]
            ],
            'by_reference' => false,
        ]);
    }
}