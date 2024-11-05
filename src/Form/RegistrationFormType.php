<?php

namespace App\Form;

use App\Entity\Warehouse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('POST')
            ->add('username', TextType::class, [
                'label' => 'Login',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Hasło',
            ])
//            ->add('roles', ChoiceType::class, [
//                'choices' => [
//                    'User' => 'ROLE_USER',
//                    'Admin' => 'ROLE_ADMIN',
//                ]
//            ])
            ->add('warehouses', EntityType::class, [
                'label' => 'Przypisz do magazynów:',
                'class' => Warehouse::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Dodaj',
            ]);
    }
}
