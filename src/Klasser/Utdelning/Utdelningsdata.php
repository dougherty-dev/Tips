<?php

/**
 * Klass Utdelningsdata.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Utdelning;

use PDO;
use Tips\Klasser\Spel;

/**
 * Klass Utdelningsdata.
 */
class Utdelningsdata {
	public int $år = 0;
	public int $vecka = 0;
	public string $tipsrad = ''; // tecken 1X2
	public string $tipsrad_012 = ''; // numerisk motsvarighet 012
	public bool $har_tipsrad = false;
	/**
	 * @var int[] $utdelning
	 */
	public array $utdelning = [0, 0, 0, 0]; // 13, 12, 11, 10 r
	/**
	 * @var int[] $vinnare
	 */
	public array $vinnare = [0, 0, 0, 0];

	/**
	 * Initiera.
	 */
	public function __construct(public Spel $spel) {
	}

	/**
	 * Hämta utdelningsdata.
	 * @return array<string, int>
	 */
	public function utdelningsdata(int $u13_min = 0, int $u13_max = MAXVINST): array {
		$utdelningsdata = [];
		$sats = $this->spel->db->instans->prepare("SELECT `omgång`, `u13` FROM `utdelning`
			WHERE `tipsrad_012` AND `u13` BETWEEN :u13_min AND :u13_max");
		$sats->bindValue(':u13_min', $u13_min, PDO::PARAM_INT);
		$sats->bindValue(':u13_max', $u13_max, PDO::PARAM_INT);
		$sats->execute();

		/**
		 * Populera utdelningsdata.
		 */
		array_map(
			fn ($rad): int =>
			$utdelningsdata[(string) $rad['omgång']] = (int) $rad['u13'],
			$sats->fetchAll(PDO::FETCH_ASSOC)
		);

		return $utdelningsdata;
	}
}
