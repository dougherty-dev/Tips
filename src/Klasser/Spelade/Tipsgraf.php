<?php

/**
 * Klass Tipsgraf.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spelade;

/**
 * Klass Tipsgraf.
 */
class Tipsgraf extends SparaFil {
	protected string $tipsgraf = 'Ingen graf tillgänglig';

	/**
	 * Rita tipsgraf från genererade tipsrader.
	 */
	public function generera_tipsgraf(): void {
		/**
		 * Markera vinnande koordinat med ett rött kors.
		 */
		$this->graf->vinstkors($this->utdelning->tipsrad_012);

		foreach ($this->tipsvektor as $tipsrad_012) {
			[$x, $y] = $this->graf->tipsgrafskoordinater($tipsrad_012);

			if ($this->utdelning->tipsrad_012) {
				$korrekta = antal_rätt($tipsrad_012, $this->utdelning->tipsrad_012);

				/**
				 * Rita vinstrader med färg och kvadrater.
				 */
				match ($korrekta) {
					13 => $this->pixla($x, $y, 4, $this->graf->blå),
					12 => $this->pixla($x, $y, 3, $this->graf->lila),
					11 => $this->pixla($x, $y, 2, $this->graf->vit),
					10 => $this->pixla($x, $y, 1, $this->graf->grön),
					default => $this->graf->sätt_pixel($x, $y, $this->graf->gul_v[$korrekta])
				};
			}

			if (!$this->utdelning->tipsrad_012) {
				$this->graf->sätt_pixel($x, $y, $this->graf->gul);
			}
		}

		$this->graf->spara_tipsgraf($this->bildfil);
		$this->tipsgraf = $this->graf->rendera_tipsgraf($this->bildfil);
		$this->utdelning->spel->db->logg->logga(
			self::class . ": ✅ Genererade {$this->bildfil}.
			({$this->utdelning->spel->omgång}-{$this->utdelning->spel->sekvens})"
		);
	}

	/**
	 * Rita enskild pixel och omgivande kvadrat för vinstrader.
	 */
	private function pixla(int $x, int $y, int $delta, int $färg): void {
		$this->graf->sätt_rektangel($x - $delta, $y - $delta, $x + $delta, $y + $delta, $färg);
		$this->graf->sätt_pixel($x, $y, $färg);
	}
}
