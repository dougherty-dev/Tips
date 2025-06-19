<?php

/**
 * Projekt Tips.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips;

use Tips\Klasser\Preludium;
use Tips\Klasser\Omgang;

define('UPPMÄRKNING', true);

require_once dirname(__DIR__) . '/vendor/autoload.php';
new Preludium();
new Omgang();
