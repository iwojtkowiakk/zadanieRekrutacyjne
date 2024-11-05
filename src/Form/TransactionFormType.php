<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Transaction;
use App\Enum\TransactionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

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
                'choice_label' => function (Product $product) {
                    return $product->getName() . ' (' . $product->getUnit() . ')';
                },
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Ilość (w jednostkach miary produktu)',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Cena jednostkowa',
            ])
            ->add('vat', NumberType::class, [
                'label' => 'VAT',
            ])
            ->add('file', FileType::class, [
                'label' => 'Plik (PDF lub XML, max. 4 pliki)',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new Assert\All([
                        new Assert\File([
                            'maxSize' => '10M',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/xml',
                            ],
                            'mimeTypesMessage' => 'Proszę wybrać odpowiedni dokument',
                        ])
                    ]),
                    new Assert\Count([
                        'min' => 0,
                        'max' => 4,
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
