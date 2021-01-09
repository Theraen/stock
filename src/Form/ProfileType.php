<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileType extends AbstractType
{
    private $translator;

        public function __construct(TranslatorInterface $translator) {
            $this->translator = $translator;
        }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('Email'),
                'required' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => $this->translator->trans('Firstname'),
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => $this->translator->trans('Lastname'),
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
