<?php

/**
 * Klass DistributionAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax;

use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Moduler\Distribution;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\Distribution\Konstanter;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
new Preludium();

/**
 * Klass DistributionAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class DistributionAjax {
	use Ajax;
	use Konstanter;

	private Spel $spel;
	private Tips $tips;
	private Distribution $distribution;

	public function __construct() {
		$this->spel = new Spel();
		$this->tips = new Tips($this->spel);
		$this->distribution = new Distribution($this->tips->utdelning, $this->tips->odds, $this->tips->streck, $this->tips->matcher);
		$this->förgrena();
	}

	/**
	 * Håll minprocent inom godtagbara intervall.
	 */
	private function distribution_minprocent(): void {
		$this->distribution->minprocent = (float) $this->db_preferenser->validera_indata('distribution_minprocent', self::DISTRIBUTION_GRUND_MIN_MIN, self::DISTRIBUTION_GRUND_MIN_MAX, self::DISTRIBUTION_GRUND_MIN_STD, 'distribution.minprocent');
		$this->distribution->maxprocent = (float) $this->db_preferenser->validera_indata('distribution_maxprocent', self::DISTRIBUTION_GRUND_MAX_MIN, self::DISTRIBUTION_GRUND_MAX_MAX, self::DISTRIBUTION_GRUND_MAX_STD, 'distribution.maxprocent');
		$this->db_preferenser->komparera_preferenser($this->distribution->minprocent, $this->distribution->maxprocent, self::DISTRIBUTION_GRUND_MIN_STD, self::DISTRIBUTION_GRUND_MAX_STD, 'distribution.minprocent', 'distribution.maxprocent');
		$this->distribution->spara_omgång($this->tips);
	}

	/**
	 * Håll grundläggande minprocent inom godtagbara intervall.
	 */
	private function grund_minprocent(): void {
		$grund_minprocent = (float) $this->db_preferenser->validera_indata('grund_minprocent', self::DISTRIBUTION_GRUND_MIN_MIN, self::DISTRIBUTION_GRUND_MIN_MAX, self::DISTRIBUTION_GRUND_MIN_STD, 'distribution.grund_minprocent');
		$grund_maxprocent = (float) $this->db_preferenser->validera_indata('grund_maxprocent', self::DISTRIBUTION_GRUND_MAX_MIN, self::DISTRIBUTION_GRUND_MAX_MAX, self::DISTRIBUTION_GRUND_MAX_STD, 'distribution.grund_maxprocent');
		$this->db_preferenser->komparera_preferenser($grund_minprocent, $grund_maxprocent, self::DISTRIBUTION_GRUND_MIN_STD, self::DISTRIBUTION_GRUND_MAX_STD, 'distribution.grund_minprocent', 'distribution.grund_maxprocent');
	}

	/**
	 * Spara attraktionsfaktor.
	 */
	private function attraktionsfaktor(): void {
		$this->ändra_attraktionsfaktor('distribution');
	}
}

new DistributionAjax();
