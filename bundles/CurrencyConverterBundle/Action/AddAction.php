<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Bundles\CurrencyConverterBundle\Form\CurrencyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddAction extends BaseAction
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(CurrencyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);
        }

        return $this->render('@CurrencyConverter/action/currency_form.html.twig', [
            'form' => $form->createView(),
            'title' => $this->trans('currencies.add'),
            'sectionTitle' => $this->trans('currencies.list'),
        ]);
    }
}
