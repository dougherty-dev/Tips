<?php

/**
 * Egenskap Varden.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Egenskaper;

use Tips\Klasser\Databas;
use Tips\Klasser\DBPreferenser;

/**
 * Egenskap Varden.
 * Ofta nyttjade dynamiska strängvärden läggs som egenskap snarare än klass.
 * API till Svenska spel, PHP-binär, antal trådar vid parallell exekvering.
 */
trait Varden {
	public const UTDELNING_13_MIN_MIN = 100;
	public const UTDELNING_13_MIN_MAX = 1000000;
	public const UTDELNING_13_MIN_STD = 10_000;

	public const UTDELNING_13_MAX_MIN = 1000;
	public const UTDELNING_13_MAX_MAX = MAXVINST;
	public const UTDELNING_13_MAX_STD = 1000_000;

	public const STD_RADER = 200;

	protected string $api = '';
	protected string $php = '';
	protected string $fcgi = '';
	protected int $trådar = TRÅDMÄNGD[2];
	public int $u13_min = self::UTDELNING_13_MIN_STD;
	public int $u13_max = self::UTDELNING_13_MAX_STD;
	public int $max_rader = -1;

	/**
	 * Hämta värden från databas.
	 * Max rader att generera samt projicerat vinstintervall för 13 rätt.
	 */
	protected function hämta_värden(Databas $db): void {
		$db_preferenser = new DBPreferenser($db);

		$this->api = $db_preferenser->hämta_preferens('preferenser.api');
		$this->php = $db_preferenser->hämta_preferens('preferenser.php');
		$this->fcgi = $db_preferenser->hämta_preferens('preferenser.fcgi');
		$this->trådar = (int) $db_preferenser->hämta_preferens('preferenser.trådar');

		$db_preferenser->int_preferens_i_intervall(
			$this->max_rader,
			MIN_RADER,
			MAX_RADER,
			self::STD_RADER,
			'preferenser.max_rader'
		);

		$db_preferenser->int_preferens_i_intervall(
			$this->u13_min,
			self::UTDELNING_13_MIN_MIN,
			self::UTDELNING_13_MIN_MAX,
			self::UTDELNING_13_MIN_STD,
			'preferenser.u13_min'
		);

		$db_preferenser->int_preferens_i_intervall(
			$this->u13_max,
			self::UTDELNING_13_MAX_MIN,
			self::UTDELNING_13_MAX_MAX,
			self::UTDELNING_13_MAX_STD,
			'preferenser.u13_max'
		);

		$db_preferenser->int_komparera_preferenser(
			$this->u13_min,
			$this->u13_max,
			self::UTDELNING_13_MIN_STD,
			self::UTDELNING_13_MAX_STD,
			'preferenser.u13_min',
			'preferenser.u13_max'
		);
	}
}
