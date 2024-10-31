<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Transaction;
use App\Entity\Warehouse;
use App\Enum\TransactionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transactionType', ChoiceType::class, [
                'choices' => [
                    TransactionType::IN->value => TransactionType::IN,
                    TransactionType::OUT->value => TransactionType::OUT,
                ]
            ])
            ->add('warehouse', EntityType::class, [
                'class' => Warehouse::class,
                'choice_label' => 'name',
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'attr' => ['id' => 'product'],
            ])
            ->add('quantity', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class)
            ->add('price', \Symfony\Component\Form\Extension\Core\Type\NumberType::class)
            ->add('vat', \Symfony\Component\Form\Extension\Core\Type\NumberType::class)
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
