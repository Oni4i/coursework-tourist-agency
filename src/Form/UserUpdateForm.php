<?php

namespace App\Form;

use App\Entity\Point\Point;
use App\Entity\User\User;
use App\Form\Components\FirstLastNameForm;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UserUpdateForm extends AbstractType
{
    private UserRepository $userRepository;
    private RouterInterface $router;

    public function __construct(
        UserRepository $userRepository,
        RouterInterface $router
    )
    {
        $this->userRepository   = $userRepository;
        $this->router           = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', FirstLastNameForm::class, [
                'data_class' => User::class,
            ])
            ->add('username', TextType::class, [
                'required'  => true,
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('password', TextType::class, [
                'data'      => '',
                'required'  => true,
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('point', EntityType::class, [
                'class'     => Point::class,
                'choice_label' => function (Point $point) {
                    return $point->getFullAddress();
                },
                'required'  => true,
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label'     => 'Update',
                'attr'      => [
                    'class' => 'btn-success'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
