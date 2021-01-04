<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
               'required' => true,
               'label' => $this->translator->trans('Email'),
               'attr' => [
                   'class' => 'form-control-user',
               ]
            ])
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => $this->translator->trans('Firstname'),
                'attr' => [
                    'class' => 'form-control-user',
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => $this->translator->trans('Lastname'),
                'attr' => [
                    'class' => 'form-control-user',
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => $this->translator->trans('Accept the terms'),
                'constraints' => [
                    new IsTrue([
                        'message' => $this->translator->trans('You must accept the terms of use'),
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => $this->translator->trans('Password'),
                'attr' => [
                    'class' => 'form-control-user',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('Enter a password'),
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => $this->translator->trans('The password must contain {{ limit }} characters minimum'),
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
