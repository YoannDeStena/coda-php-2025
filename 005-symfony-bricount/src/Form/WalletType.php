<?php

namespace App\Form;

use App\DTO\WalletDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class WalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                "constraints" => [
                    new NotBlank(message: "Le nom ne peut pas être vide"),
                    new Length(min:3,max:50, minMessage: "Le nom doit être au moins 3 caractères", maxMessage: "Le nom doit être moins de 50 caractères")
                ],
                "required" => true,
                "label" => "Nom du Portefeuille",
                "help" => "Le nom doit être entre 3 et 50 caractères"
            ])
            ->add("submit", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WalletDTO::class
        ]);
    }
}
