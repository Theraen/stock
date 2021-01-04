<?php

namespace App\Form;

use App\Data\SearchProduct;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchProductType extends AbstractType
{
    private $translator;
    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('q', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => $this->translator->trans('Search'),
            ],
        ])
        ->add('categories', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => Category::class,
            'expanded' => true,
            'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) 
    {
        $resolver->setDefaults([
            'data_class' => SearchProduct::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);

        
    }

    public function getBlockPrefix()
        {
            return '';
        }

}