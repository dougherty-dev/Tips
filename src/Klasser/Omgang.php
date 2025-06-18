<?php

/**
 * Klass Omgang.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use Tips\Klasser\Omgang\Uppmarkning;

/**
 * Klass Omgang.
 */
final class Omgang {
	private Spel $spel;
	private Tips $tips;

	/**
	 * Märk upp.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->tips = new Tips($this->spel);
		new Uppmarkning($this->tips);
	}
}
