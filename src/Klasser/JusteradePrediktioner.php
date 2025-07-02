<?php

/**
 * Klass JusteradePrediktioner.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Klasser\JusteradePrediktioner\JusteradeSannolikheter;

/**
 * Klass JusteradePrediktioner.
 */
final class JusteradePrediktioner extends JusteradeSannolikheter {
	/**
	 * @var array<int, string[]> $justerade_stilade
	 */
	public array $justerade_stilade;

	public function __construct(public Prediktioner $prediktioner) {
		parent::__construct($this->prediktioner);
		$this->hämta_historik();
	}

	/**
	 * Hämta historik.
	 */
	public function hämta_historik(): void {
		$prediktioner = $this->db_preferenser->hämta_preferens("prediktioner.historik");
		$historik = array_chunk(array_map('floatval', explode(',', $prediktioner)), 300);

		match ($prediktioner) {
			'' => $this->spara_historik(),
			default => [$this->odds_j, $this->streck_j] = [array_chunk($historik[0], 100), array_chunk($historik[1], 100)]
		};

		match ($this->prediktioner->tabell) {
			'streck' => $this->justerade_sann =
				$this->justerade_sann($this->prediktioner->sannolikheter, $this->streck_j),
			default => $this->justerade_sann =
				$this->justerade_sann($this->prediktioner->sannolikheter, $this->odds_j)
		};
	}
}
