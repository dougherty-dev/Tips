<?php

/**
 * Klass HamtaAlla.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

use PDO;

/**
 * Klass HamtaAlla.
 * Hämtar alla investeringar, till skillnad från enbart aktuell.
 */
class HamtaAlla extends Tabellrad {
	/**
	 * @var array<int, int[]> $investeringar
	 */
	protected array $investeringar = [];

	/**
	 * Variabler för att bestämma grafdimensioner.
	 */
	protected int $maxvinst = 0; // största vinsten bland samtliga investeringar
	protected int $ackumulerad_vinst = 0; // summan av alla vinster
	protected int $ackumulerad_utgift = 0; // summan av alla erlagda betalningar
	protected int $ackmin = 0; // minimum för ackumulerad vinst
	protected int $ackmax = 0; // maximum för ackumulerad vinst

	/**
	 * Hämta alla investeringar och beräkna statistik.
	 */
	protected function hämta_investeringar(): void {
		$vinstmatris = TOM_VINSTMATRIS;
		$ack = 0;
		$antal = $this->antal_investeringar();
		$this->tabell = (new Tabellhuvud())->tabellhuvud();

		/**
		 * Hämta investeringar i tidsordning.
		 */
		$sats = $this->tips->spel->db->instans->prepare(
			"SELECT `omgång`, `speltyp`, `sekvens`, `genererade`,
			`valda`, `vinst`, `tid`, `u10`, `u11`, `u12`, `u13`
			FROM `investerade` ORDER BY `tid`"
		);
		$sats->execute();

		/**
		 * Iterera över investeringar
		 */
		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $rad) {
			$ack += intval($rad['vinst']) - intval($rad['valda']); // Plussa vinst minus utlägg för spel
			$this->ackmax = max($this->ackmax, $ack); // Uppdatera max för ackumulerad vinst
			$this->ackmin = min($this->ackmin, $ack); // Uppdatera min för ackumulerad vinst

			$this->investeringar[] = [$rad['vinst'], $rad['vinst'] - $rad['valda']]; // brutto och netto
			$this->maxvinst = max($this->maxvinst, $rad['vinst']); // Uppdatera maxvinst
			$this->ackumulerad_vinst += $rad['vinst']; // Räkna upp ackumulerad vinst
			$this->ackumulerad_utgift += $rad['valda']; // Räkna upp ackumulerad utgift

			/**
			 * Struktur för vinstkategorier.
			 */
			$vinstdata = [
				10 => (int) $rad['u10'],
				11 => (int) $rad['u11'],
				12 => (int) $rad['u12'],
				13 => (int) $rad['u13']
			];

			/**
			 * Räkna upp statistik vid vinst.
			 */
			foreach (array_keys($vinstmatris) as $index) {
				if ($vinstdata[$index] > 0) {
					$vinstmatris[$index]++;
				}
			}

			$this->tabellrad($antal, $rad, $vinstdata);
		}

		$this->statistik($vinstmatris); // Visa statistik över vinster
	}
}
