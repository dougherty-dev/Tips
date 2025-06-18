<?php

/**
 * Egenskap Konstanter.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANN;

if (!extension_loaded('fann')) {
	return;
}

/**
 * Egenskap Konstanter.
 */
trait Konstanter {
	public const FANN_MIN = 8;
	public const FANN_MAX = MATCHRYMD;
	public const FANN_STD = 10;

	public const FANN_FELTOLERANS_MIN = 0.0;
	public const FANN_FELTOLERANS_MAX = 0.25;
	public const FANN_FELTOLERANS_STD = 0.16;

	public const FANN = MODULER . '/FANNGenerera';
}
