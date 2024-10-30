<?php

namespace App\Form;

use App\Entity\Sweatshirt;
use App\Form\StockForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Image;

class SweatShirtForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour le nom du sweatshirt
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Enter un nom', 
                    'class' => 'form-control border border-dark rounded text-center' 
                ],
            ])
            // Champ pour le fichier d'image (thumbnail)
            ->add('thumbnailFile', FileType::class, [
                'mapped' => false, // Non lié à l'entité
                'required' => false, // Optionnel
                'constraints' => [
                    new Image(
                        minWidth: 200,
                        maxWidth: 400, 
                        minHeight: 200, 
                        maxHeight: 400 
                    )
                ]
            ])
            // Champ pour le prix
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'scale' => 2, 
                'attr' => [
                    'step' => '0.01', 
                    'placeholder' => 'Enter un prix',
                    'class' => 'form-control border border-dark rounded text-center',
                ],
            ])
            // Collection de stocks
            ->add('stocks', CollectionType::class, [
                'entry_type' => StockForm::class, 
                'allow_add' => true, 
                'allow_delete' => true, 
                'by_reference' => false, 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sweatshirt::class, // Classe de données associée
        ]);
    }
}
