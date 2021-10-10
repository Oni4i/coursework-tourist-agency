<?php

namespace App\Form;

use App\Entity\Point\Point;
use App\Entity\User\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UserCreateForm extends AbstractType
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
            ->add('firstName', TextType::class, [
                'required'  => true,
                'attr'      => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'required'  => true,
                'attr'      => [
                    'class' => 'form-control'
                ]
            ])
            ->add('username', TextType::class, [
                'data'      => $this->getGeneratedUserLogin(),
                'required'  => true,
                'attr'      => [
                    'class' => 'form-control'
                ]
            ])
            ->add('password', TextType::class, [
                'data'      => $this->getGeneratedPassword(),
                'required'  => true,
                'attr'      => [
                    'class' => 'form-control'
                ]
            ])
            ->add('point', EntityType::class, [
                'class'     => Point::class,
                'choice_label' => static function (Point $point) {
                    return \sprintf('%s, %s', $point->getCity(), $point->getAddress());
                },
                'required'  => true,
                'attr'  => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label'     => 'Create',
                'attr'      => [
                    'class' => 'btn-success'
                ],
            ]);
    }

    public function getGeneratedUserLogin(): string
    {
        return \sprintf('user_%d', \strtotime('now'));
    }

    public function getGeneratedPassword(): string
    {
        return \sprintf(
            '%d%d',
            \random_int(0, 9999999),
            \strtotime('now - 20 years')
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
