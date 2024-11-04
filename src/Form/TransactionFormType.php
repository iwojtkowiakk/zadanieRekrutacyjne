<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Transaction;
use App\Entity\Warehouse;
use App\Enum\TransactionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TransactionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transactionType', ChoiceType::class, [
                'label' => 'Typ transakcji',
                'choices' => [
                    "Przyjęcie" => TransactionType::IN,
                    "Wydanie" => TransactionType::OUT,
                ],
            ])
            ->add('product', EntityType::class, [
                'label' => 'Produkt',
                'class' => Product::class,
                'choice_label' => 'name',
            ])
            ->add('quantity', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class, [
                'label' => 'Liczba',
            ])
            ->add('price', \Symfony\Component\Form\Extension\Core\Type\NumberType::class, [
                'label' => 'Cena jednostkowa',
            ])
            ->add('vat', \Symfony\Component\Form\Extension\Core\Type\NumberType::class, [
                'label' => 'VAT',
            ])
            ->add('file', FileType::class, [
                'label' => 'Plik (PDF lub XML)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10000000',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/xml',
                        ],
                        'mimeTypesMessage' => 'Proszę wybrać odpowiedni dokument',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Zapisz',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
