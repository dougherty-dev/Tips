<?php

/**
 * Klass PreferenserAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use Tips\Klasser\Preludium;
use Tips\Klasser\Databas;
use Tips\Egenskaper\Ajax;
use Tips\Inkludera\Konstanter;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass PreferenserAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class PreferenserAjax {
	use Ajax;
	use Konstanter;

	/**
	 * Inititiera.
	 */
	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Spara preferens för API.
	 * js/funktioner.js: api
	 */
	private function api(): void {
		$this->db_preferenser->spara_preferens('preferenser.api', (string) filter_var($_REQUEST['api'], FILTER_SANITIZE_SPECIAL_CHARS));
	}

	/**
	 * Spara preferens för PHP.
	 * js/funktioner.js: php, fcgi
	 */
	private function php(): void {
		$this->db_preferenser->spara_preferens(
			'preferenser.php',
			(string) filter_var($_REQUEST['php'], FILTER_SANITIZE_SPECIAL_CHARS)
		);

		$this->db_preferenser->spara_preferens(
			'preferenser.fcgi',
			(string) filter_var($_REQUEST['fcgi'], FILTER_SANITIZE_SPECIAL_CHARS)
		);
	}

	/**
	 * Spara preferens för trådar.
	 * js/funktioner.js: trådar
	 */
	private function trådar(): void {
		$trådar = filter_var($_REQUEST['trådar'], FILTER_VALIDATE_INT);
		$trådar = (in_array($trådar, TRÅDMÄNGD, true)) ? $trådar : 9;
		$this->db_preferenser->spara_preferens('preferenser.trådar', (string) $trådar);
	}

	/**
	 * Spara preferens för avlusning.
	 * js/funktioner.js: avlusa
	 */
	private function avlusa(): void {
		$this->db_preferenser->spara_preferens('preferenser.avlusa', (string) filter_var($_REQUEST['avlusa'], FILTER_VALIDATE_BOOLEAN));
	}

	/**
	 * Spara anal rader att generera.
	 * js/funktioner.js: antal_rader
	 */
	private function antal_rader(): void {
		$this->db_preferenser->validera_indata('antal_rader', MIN_RADER, MAX_RADER, MIN_RADER, 'preferenser.max_rader');
	}

	/**
	 * Spara vinstintervall för 13 rätt.
	 * js/funktioner.js: u13_min, u13_max
	 */
	private function u13_min(): void {
		$u13_min = (int) $this->db_preferenser->validera_indata(
			'u13_min',
			self::U13_MIN_MIN,
			self::U13_MIN_MAX,
			self::U13_MIN_STD,
			'preferenser.u13_min'
		);

		$u13_max = (int) $this->db_preferenser->validera_indata(
			'u13_max',
			self::U13_MAX_MIN,
			self::U13_MAX_MAX,
			self::U13_MAX_STD,
			'preferenser.u13_max'
		);

		$this->db_preferenser->komparera_preferenser(
			$u13_min,
			$u13_max,
			self::U13_MIN_STD,
			self::U13_MAX_STD,
			'preferenser.u13_min',
			'preferenser.u13_max'
		);
	}
}

new PreferenserAjax();
