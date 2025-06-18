<?php

/**
 * Klass Prediktioner.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Klasser\Prediktioner\Tipsdata;

/**
 * Klass Prediktioner.
 */
final class Prediktioner extends Tipsdata {
	public Utfallshistorik $utfallshistorik;
	public JusteradePrediktioner $justerade_pred;
	public string $enkelrad_012 = '';
	public string $enkelrad = '';
	/** @var int[] $sort_sannolikheter */ public array $sort_sannolikheter;
	/** @var array<int, string[]> $stil_sannolikheter */ public array $stil_sannolikheter;
	/** @var int[] $sortering */ public array $sortering;
	/** @var string[] $sorteringsstil */ public array $sorteringsstil;

	/**
	 * Initiera.
	 */
	public function __construct(public Spel $spel, public string $tabell) {
		parent::__construct($this->spel, $this->tabell);
		$this->hämta_prediktioner();
		$this->prediktioner_till_sannolikheter();

		$this->justerade_pred = new JusteradePrediktioner($this);
		$this->sort_sannolikheter = ordna_sannolikheter($this->sannolikheter);

		$this->sannolikheter_till_enkelrad();
		$this->stila_prediktioner();

		if ($this->tabell === 'odds') {
			$this->utfallshistorik = new Utfallshistorik($this);
		}
	}

	/**
	 * Beräkna enkelrad från sannolikheter.
	 */
	private function sannolikheter_till_enkelrad(): void {
		$this->enkelrad_012 = sannolikheter_till_enkelrad_012($this->sannolikheter);
		$this->enkelrad = siffror_till_symboler($this->enkelrad_012);
	}

	/**
	 * Ge prediktioner nyans efter sannolikhet.
	 */
	private function stila_prediktioner(): void {
		$this->stil_sannolikheter = stila_tabell($this->sannolikheter);
		$this->justerade_pred->justerade_stilade = stila_tabell($this->justerade_pred->justerade_sann);

		foreach (array_keys($this->sannolikheter) as $i) {
			$sortering = $this->sortering[] = $this->sort_sannolikheter[$i] + 1;
			$this->sorteringsstil[] = stil(1 - $sortering / MATCHANTAL);
		}
	}
}
