<?php

namespace Bundles\CurrencyConverterBundle\Form;

use Bundles\CurrencyConverterBundle\DTO\CurrencyDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ConverterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('from_currency', ChoiceType::class, [
                'label' => 'currencies.form.from_currency.label',
                'choices' => $options['currencies'],
                'choice_label' => fn(CurrencyDTO $currency) => '[' . $currency->code . '] ' . $currency->title,
                'placeholder' => 'currencies.form.from_currency.placeholder',
                'constraints' => [
                    new NotBlank(message: 'currencies.form.from_currency.not_blank'),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('to_currency', ChoiceType::class, [
                'label' => 'currencies.form.to_currency.label',
                'choices' => $options['currencies'],
                'choice_label' => fn(CurrencyDTO $currency) => '[' . $currency->code . '] ' . $currency->title,
                'placeholder' => 'currencies.form.to_currency.placeholder',
                'constraints' => [
                    new NotBlank(message: 'currencies.form.to_currency.not_blank'),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('amount', TextType::class, [
                'label' => 'currencies.form.amount.label',
                'constraints' => [
                    new NotBlank(message: 'currencies.form.amount.not_blank'),
                    new GreaterThan(0, message: 'currencies.form.amount.must_be_positive'),
                    new Type(['type' => 'numeric', 'message' => 'currencies.form.amount.must_be_numeric']),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'currencies.form.submit.label',
                'attr' => ['class' => 'btn btn-secondary btn-icon-split'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'currencies' => [],
            'translation_domain' => 'CurrencyConverterBundle',
        ]);
    }
}
