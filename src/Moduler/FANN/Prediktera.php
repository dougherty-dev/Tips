<?php

/**
 * Klass Prediktera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANN;

/**
 * Klass Prediktera.
 */
class Prediktera extends Preferenser {
	/**
	 * @var resource $fann
	 */
	public $fann;

	/**
	 * @var float[] $rådata
	 */
	public array $rådata = NOLLRAD;

	 /**
	  * @var string[] $utdata
	  */
	public array $utdata = TOMRAD;

	protected bool $exists_fann = false; // huruvida anslutning till FANN existerar
	protected int $fann_rätt = 0;
	protected int $halvgarderingar = 0;

	/**
	 * Beräkna FANN-rad för aktuella sannolikheter.
	 */
	protected function prediktera(): void {
		if ($this->exists_fann) {
			foreach ($this->odds->sannolikheter as $index => $o_sannolikhet) {
				$s_sannolikhet = $this->streck->sannolikheter[$index];
				/**
				 * FANN-modellen består av en kombination av streck och odds.
				 */
				$res = fann_run(
					$this->fann,
					[$o_sannolikhet[0],
					$s_sannolikhet[0],
					$o_sannolikhet[1],
					$s_sannolikhet[1],
					$o_sannolikhet[2],
					$s_sannolikhet[2]]
				);
				$this->rådata[$index] = $res[0];
			}

			$this->utdata = $this->beräkna_utdata($this->rådata, $this->limiter);
			$this->halvgarderingar = array_sum(
				array_map(fn ($str): int => count(str_split($str)) - 1, $this->utdata)
			);
			$this->fann_rätt = $this->fann_rätt($this->utdata, $this->utdelning->tipsrad_012);
		}
	}

	/**
	 * Beräkna utdata.
	 * @param float[] $rådata
	 * @param float[] $limiter
	 * @return string[]
	 */
	public function beräkna_utdata(array $rådata, array $limiter): array {
		$utdata = TOMRAD;
		$sort = $rådata;
		asort($sort);
		$sort = array_slice($sort, 0, 3, true);

		/**
		 * Spik eller halvgardering bestäms av resultatet av FANN-körningen.
		 */
		foreach ($rådata as $index => $värde) {
			$utdata[$index] = match (true) {
				$värde < $limiter[0] => '0', // 1
				$värde < $limiter[1] => '01', // 1X
				$värde < $limiter[2] => '12', // X2
				default => '2', // 2
			};
		}
		return $utdata;
	}

	/**
	 * Beräkna antal rätt för FANN-rad.
	 * @param string[] $utdata
	 */
	protected function fann_rätt(array $utdata, string $tipsrad_012): int {
		return ($tipsrad_012 === '') ? 0 : antal_rätt($utdata, $tipsrad_012);
	}

	/**
	 * Beräkna antal rader som täcks av helgarderingar givna av FANN.
	 */
	protected function beräkna_rader(int $fann_min): int {
		for ($index = $fann_min, $antal_rader = 0; $index <= MATCHANTAL; $index++) {
			$antal_rader += UTFALL_PER_HALVGARDERINGAR[$this->halvgarderingar][$index];
		}
		return $antal_rader;
	}
}
