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
	public const int FANN_MIN = 8;
	public const int FANN_MAX = MATCHRYMD;
	public const int FANN_STD = 10;

	public const float FANN_FELTOLERANS_MIN = 0.0;
	public const float FANN_FELTOLERANS_MAX = 0.25;
	public const float FANN_FELTOLERANS_STD = 0.16;

	public const string FANN = MODULER . '/FANNGenerera';
}
