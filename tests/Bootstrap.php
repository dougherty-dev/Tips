<?php

/**
 * Autoload (bootstrap)
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests;

use Tips\Klasser\Spel;
use Tips\Klasser\DBPreferenser;
use Tips\Klasser\Preludium;

final class Bootstrap {
	public function __construct() {
		rename(
			__DIR__ . '/../src/_data/_test',
			__DIR__ . '/../src/_data/test'
		);

		require_once __DIR__ . '/../vendor/autoload.php';
		new Preludium();
		$spel = new Spel();
		$db_preferenser = new DBPreferenser($spel->db);
		$db_preferenser->spara_preferens('preferenser.php', PHP_BINARY);
	}
}

new Bootstrap();
