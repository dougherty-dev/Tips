<?php

/**
 * Klass Felhanterare.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

/**
 * Klass Felhanterare.
 */
class Felhanterare {
	/**
	 * Definiera felhanterare.
	 */
	public function felhanterare(int $errno, string $errstr, string $errfile, int $errline): bool {
		/**
		 * Metoden anropas flera gånger, undvik omdefiniering.
		 */
		if (!defined('FEL')) {
			define('FEL', 1);
		}

		$error = match ($errno) {
			E_NOTICE, E_USER_NOTICE, E_DEPRECATED, E_USER_DEPRECATED => 'Notice',
			E_WARNING, E_USER_WARNING => 'Warning',
			E_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR => 'Fatal Error',
			default => 'Unknown'
		};

		(new Databas())->logg->logga(self::class . ": $error: $errstr in $errfile on line $errline");
		return true;
	}
}
