<?php

/**
 * Klass Generera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Kluster;

/**
 * Klass Generera.
 */
class Generera extends Kluster {
	/**
	 * Generera rektanglar från koordinater.
	 * @param array<int, int[]> $koordinater
	 */
	protected function rektanglar(array $koordinater): void {
		$rektanglar = [];
		foreach ($this->rektanglar as [$x_min, $y_min, $x_max, $y_max]) {
			$rektanglar[] = implode(',', [$x_min, $y_min, $x_max, $y_max]);
			foreach ($koordinater as $index => [$x, $y]) {
				if (∈($x, $x_min, $x_max) && ∈($y, $y_min, $y_max)) {
					$this->klustrade_rader++;
					unset($koordinater[$index]);
				}
			}
		}

		$this->db_preferenser->spara_preferens('kluster.rektanglar', implode(',', $rektanglar));
		$this->db_preferenser->spara_preferens('kluster.area', (string) $this->area);
		$this->db_preferenser->spara_preferens('kluster.antal_rader', (string) $this->antal_rader);
		$this->db_preferenser->spara_preferens('kluster.klustrade_rader', (string) $this->klustrade_rader);
		$this->graf->spara_tipsgraf($this->kombinationsgraf);
		$this->odds->spel->db->logg->logga(self::class . ': ✅ Fann kluster.');
	}

	/**
	 * Generera kluster från hämtade koordinater.
	 * @param array<int, int[]> $koordinater
	 * @return array<int, mixed[]>
	 */
	protected function generera_kluster($koordinater): array {
		$kluster = [];
		while (count($koordinater)) {
			$kluster[] = $this->kluster((array) array_pop($koordinater), $koordinater);
		}
		rsort($kluster);
		return $kluster;
	}
}
