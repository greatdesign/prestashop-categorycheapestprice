<?php

use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

class DummymodulenameListModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
        echo 'heelo';
    }
}
