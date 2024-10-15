<?php

namespace Bundles\CurrencyConverterBundle\Action;

use Symfony\Component\HttpFoundation\Response;

class DashboardAction extends BaseAction
{
    public function __invoke(): Response
    {
        return $this->render('@CurrencyConverter/action/dashboard.html.twig');
    }
}
