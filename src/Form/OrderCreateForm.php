<?php

namespace App\Form;

use App\Entity\Customer\Customer;
use App\Entity\Order\Order;
use App\Entity\Voucher\Voucher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class OrderCreateForm extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', EntityType::class, [
                'class'         => Customer::class,
                'choice_label'  => static function (Customer $customer) {
                    return $customer->getChoiceLabel();
                },
                'required'      => true,
                'constraints'   => [
                    new NotNull(),
                ],
            ])
            ->add('voucher', EntityType::class, [
                'class'         => Voucher::class,
                'choice_label'  => static function (Voucher $voucher) {
                    return $voucher->getChoiceLabel();
                },
                'required'      => true,
                'constraints'   => [
                    new NotNull(),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label'         => 'Create',
                'attr'          => [
                    'class' => 'btn-success',
                ],
            ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}