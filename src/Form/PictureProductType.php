<?php

namespace App\Form;

use App\Entity\PictureStock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PictureProductType extends AbstractType
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
                'label' => $this->translator->trans('Picture name'),
            ])
            ->add('picture', FileType::class, [
                'required' => true,
                'label' => $this->translator->trans('Picture'),
            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PictureStock::class,
        ]);
    }
}
