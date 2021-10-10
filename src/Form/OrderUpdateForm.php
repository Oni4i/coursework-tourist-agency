<?php

namespace App\Form;

use App\Entity\Customer\Customer;
use App\Entity\Order\Order;
use App\Entity\User\User;
use App\Entity\Voucher\Voucher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderUpdateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', EntityType::class, [
                'data_class' => Customer::class,
                'choice_label' => static function (User $user) {
                    return \sprintf('%d. %s', $user->getId(), $user->getFullName());
                },
            ])
            ->add('voucher', EntityType::class, [
                'data_class' => Voucher::class,
                'choice_label' => static function (Voucher $voucher) {
                    return \sprintf(
                        '%s. %s - %s',
                        $voucher->getTitle(),
                        $voucher->getFromPlace(),
                        $voucher->getToPlace()
                    );
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}