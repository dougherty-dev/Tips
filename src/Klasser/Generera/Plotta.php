<?php

/**
 * Klass Plotta.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Generera;

use Tips\Egenskaper\Varden;
use Tips\Klasser\Tips;
use Tips\Klasser\Graf;

/**
 * Klass Plotta.
 */
class Plotta extends Tipsrader {
	use Varden;

	protected Graf $graf;
	protected string $bildfil = '';
	protected string $kombinerad_bildfil = '';
	protected int $antal_utvalda_rader = 0;

	/**
	 * Uppdatera konstruktor.
	 */
	public function __construct(protected Tips $tips) {
		parent::__construct($tips);
		$this->graf = new Graf();
		$this->bildfil = GENERERADE . "/{$this->tips->spel->filnamn}.png";
		$this->kombinerad_bildfil = GENERERADE . "/{$this->tips->spel->filnamn}-kombinerad.png";
	}

	/**
	 * Rendera tipsgraf.
	 */
	protected function plotta_genererad_tipsgraf(): void {
		$this->pixla_rader($this->graf->gul);
		$this->välj_ut_rader();
		$this->pixla_rader($this->graf->röd);
	}

	/**
	 * Pixla enskilda rader.
	 */
	private function pixla_rader(int $färg): void {
		foreach ($this->tips->spelade->tipsvektor as $tipsrad_012) {
			[$x, $y] = $this->graf->tipsgrafskoordinater($tipsrad_012);
			$this->graf->sätt_pixel($x, $y, $färg);
		}
	}

	/**
	 * Välj max_rader slumpade rader.
	 */
	private function välj_ut_rader(): void {
		if ($this->antal_genererade > $this->max_rader) {
			shuffle($this->tips->spelade->tipsvektor);
			$this->tips->spelade->tipsvektor = array_slice($this->tips->spelade->tipsvektor, 0, (int) $this->max_rader);
			sort($this->tips->spelade->tipsvektor, SORT_STRING);
		}
		$this->antal_utvalda_rader = count($this->tips->spelade->tipsvektor);
	}
}
