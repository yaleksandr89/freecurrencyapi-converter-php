<?php

namespace Bundles\CurrencyConverterBundle\Form;

use Bundles\CurrencyConverterBundle\DTO\CurrencyDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class HistoricalRatesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'currencies.form.date.label',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(message: 'currencies.form.date.not_blank'),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('base_currency', ChoiceType::class, [
                'label' => 'currencies.form.base_currency.label',
                'choices' => $options['currencies'],
                'choice_label' => fn(CurrencyDTO $currency) => $currency->title . ' (' . $currency->code . ')',
                'placeholder' => 'currencies.form.base_currency.placeholder',
                'constraints' => [
                    new NotBlank(message: 'currencies.form.base_currency.not_blank'),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('currencies', ChoiceType::class, [
                'label' => 'currencies.form.currencies.label',
                'choices' => $options['currencies'],
                'choice_label' => fn(CurrencyDTO $currency) => $currency->title . ' (' . $currency->code . ')',
                'placeholder' => 'currencies.form.currencies.placeholder',
                'multiple' => true,
                'expanded' => false,
                'constraints' => [
                    new NotBlank(message: 'currencies.form.currencies.not_blank'),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'currencies.form.submit.label',
                'attr' => ['class' => 'btn btn-primary'],
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
