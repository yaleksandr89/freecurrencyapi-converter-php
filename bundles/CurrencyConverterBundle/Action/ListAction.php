<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Symfony\Component\HttpFoundation\Response;

class ListAction extends BaseAction
{
    public function __invoke(): Response
    {
        return $this->render('@CurrencyConverter/action/list.html.twig', [
            'currencies' => $this->currencyRepository->findBy([], ['id' => 'ASC']),
        ]);
    }
}
