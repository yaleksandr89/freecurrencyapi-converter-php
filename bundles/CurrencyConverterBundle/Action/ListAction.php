<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Symfony\Component\HttpFoundation\Response;

class ListAction extends BaseAction
{
    public function __invoke(): Response
    {
        // Вывод списка валют
        return $this->render('@CurrencyConverter/action/list.html.twig');
    }
}
