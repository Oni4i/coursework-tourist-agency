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

class UserCreateForm extends AbstractType
{
    private UserRepository $userRepository;
    private RouterInterface $router;

    /**
     * @param UserRepository $userRepository
     * @param RouterInterface $router
     */
    public function __construct(
        UserRepository $userRepository,
        RouterInterface $router
    )
    {
        $this->userRepository   = $userRepository;
        $this->router           = $router;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FirstLastNameForm::class, [
                'data_class'    => User::class,
            ])
            ->add('username', TextType::class, [
                'data'          => $this->getGeneratedUserLogin(),
                'required'      => true,
                'attr'          => [
                    'class' => 'form-control',
                ],
            ])
            ->add('password', TextType::class, [
                'data'          => $this->getGeneratedPassword(),
                'required'      => true,
                'attr'          => [
                    'class' => 'form-control',
                ],
            ])
            ->add('point', EntityType::class, [
                'class'         => Point::class,
                'choice_label'  => static function (Point $point) {
                    return \sprintf(
                        '%s, %s',
                        $point->getCity(),
                        $point->getAddress()
                    );
                },
                'required'      => true,
                'attr'          => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label'         => 'Create',
                'attr'          => [
                    'class'     => 'btn-success',
                ],
            ])
        ;
    }

    /**
     * Generate random login (optional)
     *
     * @return string
     */
    public function getGeneratedUserLogin(): string
    {
        return \sprintf('user_%d', \strtotime('now'));
    }

    /**
     * Generate random password (optional)
     *
     * @return string
     */
    public function getGeneratedPassword(): string
    {
        return \sprintf(
            '%d%d',
            \random_int(0, 9999999),
            \strtotime('now - 20 years')
        );
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
