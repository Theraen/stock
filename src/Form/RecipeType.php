<?php

namespace App\Form;

use App\Entity\CategoryRecipe;
use App\Entity\IngredientRecipe;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class RecipeType extends AbstractType
{

    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nb_person', IntegerType::class, [
                'required' => true,
                'label' => $this->translator->trans('Number of person')
            ])
            ->add('preparation_time', IntegerType::class, [
                'required' => true,
                'label' => $this->translator->trans('Preparation time')
            ])
            ->add('cooking_time', IntegerType::class, [
                'required' => true,
                'label' => $this->translator->trans('Cooking time')
            ])
            ->add('picture', FileType::class, [
                'required' => true,
                'label' => $this->translator->trans('Picture'),
            ])
            ->add('categoryRecipes', EntityType::class, [
                'required' => true,
                'class' => CategoryRecipe::class,
                'label' => $this->translator->trans('Categories'),
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('ingredients', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'entry_type' => IngredientRecipeType::class,
                'entry_options' => [
                    'label' => false,
                ],
            ])
            ->add('preparation', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'entry_type' => PreparationType::class,
                'entry_options' => [
                    'label' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
