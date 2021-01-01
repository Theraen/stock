<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\PictureStock;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom du produit',
            ])
            ->add('quantity', IntegerType::class, [
                'required' => true,
                'label' => "Quantité",
            ])
            ->add('dlc', DateType::class, [
                'required' => false,
                'label' => 'Date Limite de Consomation',
                'widget' => "single_text",
            ])
            ->add('capacity', IntegerType::class, [
                'required' => true,
                'label' => 'Contenance',
            ])
            ->add('unit_measure_capacity', ChoiceType::class, [
                'required' => true,
                'label' => 'Unité de mesure',
                'attr' => [
                    'class' => 'selectpicker show-tick', 
                ],
                'choices' => [
                    'Masse' => [
                        'kg' => 'kg',
                        'g' => 'g', 
                    ],
                    'Volume' => [
                        'l' => 'l',
                        'cl' => 'cl',
                        'ml' => 'ml',
                    ], 
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Categorie',
                'class' => Category::class,
                "multiple" => false,
                'attr' => [
                    'class' => 'selectpicker show-tick', 
                ],
            ])
            ->add('pictureStock', EntityType::class, [
                'label' => 'Image',
                'class' => PictureStock::class,
                "multiple" => false,
                'attr' => [
                    'class' => 'selectpicker show-tick', 
                    'data-live-search' => true,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
