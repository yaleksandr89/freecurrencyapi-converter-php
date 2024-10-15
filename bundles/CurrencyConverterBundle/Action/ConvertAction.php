<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Symfony\Component\HttpFoundation\Response;

class ConvertAction extends BaseAction
{
    public function __invoke(): Response
    {
        // Конвертация...
        return new Response('ok...');
    }
}
