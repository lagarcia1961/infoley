<?php

use App\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
