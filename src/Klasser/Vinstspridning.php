<?php

/**
 * Klass Vinstspridning.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Klasser\Vinstspridning\Rita;

/**
 * Klass Vinstspridning.
 */
final class Vinstspridning extends Rita {
	/**
	 * Visa vinstspridning.
	 */
	public function visa_vinstspridning(): string {
		if ($this->tipsrad_012 === '') {
			return '';
		}

		$this->graf = new Graf();
		$this->bildfil = VINSTSPRIDNING . "/{$this->tipsrad_012}.png";
		if (!file_exists(GRAF . $this->bildfil)) {
			$this->rita();
		}

		return $this->graf->rendera_tipsgraf($this->bildfil);
	}
}
