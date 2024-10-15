<?php

namespace Bundles\CurrencyConverterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CurrencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'currencies.form.title.label',
                'required' => true,
                'trim' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'currencies.form.title.placeholder',
                ],
                'constraints' => [
                    new NotBlank(message: 'currencies.form.title.not_blank'),
                ],
            ])
            ->add('code', TextType::class, [
                'label' => 'currencies.form.code.label',
                'required' => true,
                'trim' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'currencies.form.code.placeholder',
                ],
                'constraints' => [
                    new NotBlank(message: 'currencies.form.code.not_blank'),
                ],
            ])
            ->add('rate', TextType::class, [
                'label' => 'currencies.form.rate.label',
                'required' => true,
                'trim' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'currencies.form.rate.placeholder',
                ],
                'constraints' => [
                    new NotBlank(message: 'currencies.form.rate.not_blank'),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'currencies.form.submit.label',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'CurrencyConverterBundle',
        ]);
    }
}
