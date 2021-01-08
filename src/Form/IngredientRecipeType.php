<?php

namespace App\Form;

use App\Entity\IngredientRecipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class IngredientRecipeType extends AbstractType
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
                'label' => $this->translator->trans('Ingredient name'),
            ])
            ->add('unit', IntegerType::class, [
                'required' => true,
                'label' => $this->translator->trans('Quantity'),
            ])
            ->add('unit_measure', ChoiceType::class, [
                'required' => true,
                'label' => $this->translator->trans('Unit of measure'),
                'choices' => [
                    $this->translator->trans('gram') => $this->translator->trans('gram'),
                    $this->translator->trans('unit') => $this->translator->trans('unit'),
                    $this->translator->trans('tablespoon') => $this->translator->trans('tablespoon'),
                    $this->translator->trans('teaspoon') => $this->translator->trans('teaspoon'),
                    $this->translator->trans('pinch') => $this->translator->trans('pinch'),
                ]
            ])
            ->add('optional', CheckboxType::class, [
                'label' => $this->translator->trans('Optional'),
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IngredientRecipe::class,
        ]);
    }
}
