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
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductType extends AbstractType
{

    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => $this->translator->trans('Product name'),
            ])
            ->add('quantity', IntegerType::class, [
                'required' => true,
                'label' => $this->translator->trans('Quantity'),
            ])
            ->add('dlc', DateType::class, [
                'required' => false,
                'label' => $this->translator->trans('Consumption Deadline'),
                'widget' => "single_text",
            ])
            ->add('capacity', IntegerType::class, [
                'required' => true,
                'label' => $this->translator->trans('Capacity'),
            ])
            ->add('unit_measure_capacity', ChoiceType::class, [
                'required' => true,
                'label' => $this->translator->trans('Unit of measure'),
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
                'label' => $this->translator->trans('Category'),
                'class' => Category::class,
                "multiple" => false,
                'attr' => [
                    'class' => 'selectpicker show-tick', 
                ],
            ])
            ->add('pictureStock', EntityType::class, [
                'label' => $this->translator->trans('Picture'),
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
