<?php

/**
 * Autoload (bootstrap)
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests;

final class Bootstrap {
	public function __construct() {
		rename(
			__DIR__ . '/../src/_data/_test',
			__DIR__ . '/../src/_data/test'
		);

		require_once __DIR__ . '/../vendor/autoload.php';
	}
}

new Bootstrap();
