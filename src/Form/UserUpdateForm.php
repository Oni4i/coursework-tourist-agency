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
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
            ->add('firstName', TextType::class, [
                'required'  => true,

            ])
            ->add('lastName', TextType::class, [
                'required'  => true,
            ])
            ->add('username', TextType::class, [
                'required'  => true,
            ])
            ->add('password', TextType::class, [
                'data'      => '',
                'required'  => true,
            ])
            ->add('point', EntityType::class, [
                'class'     => Point::class,
                'choice_label' => function (Point $point) {
                    return \sprintf('%s, %s', $point->getCity(), $point->getAddress());
                },
                'required'  => true,
            ])
            ->add('cancel', ButtonType::class, [
                'label'     => 'Cancel',
                'attr'      => [
                    'href'      => $this->router->generate('user_index'),
                    'onClick'   => 'window.location = this.getAttribute("href")',
                    'class' => 'btn btn-danger'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'     => 'Update',
                'attr'      => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
