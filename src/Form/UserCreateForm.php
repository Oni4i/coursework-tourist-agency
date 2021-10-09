<?php

namespace App\Form;

use App\Entity\Point\Point;
use App\Entity\User\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreateForm extends AbstractType
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
                'data'      => $this->getGeneratedUserLogin(),
                'required'  => true,
            ])
            ->add('password', TextType::class, [
                'data'      => $this->getGeneratedPassword(),
                'required'  => true,
            ])
            ->add('point', EntityType::class, [
                'class'     => Point::class,
                'choice_label' => function (Point $point) {
                    return \sprintf('%s, %s', $point->getCity(), $point->getAddress());
                },
                'required'  => true,
            ])
            ->add('submit', SubmitType::class, [
                'label'     => 'Create',
            ]);
    }

    public function getGeneratedUserLogin(): string
    {
        $user   = $this->userRepository->getLastUser();
        $id     = $user ? $user->getId() + 1 : 1;

        return \sprintf('user_%d', $id);
    }

    public function getGeneratedPassword(): string
    {
        return \sprintf(
            '%s_%d',
            $this->getGeneratedUserLogin(),
            \strtotime('now'),
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
