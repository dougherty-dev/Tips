<?php

/**
 * Klass KlusterAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax;

use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Moduler\Kluster;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\Kluster\Konstanter;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../../vendor/autoload.php';
new Preludium();

/**
 * Klass KlusterAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class KlusterAjax {
	use Ajax;
	use Konstanter;

	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Håll min antal per kluster inom godtagbara intervall.
	 */
	private function min_antal(): void {
		$this->db_preferenser->validera_indata('min_antal', self::KLUSTER_MIN_ANTAL_MIN, self::KLUSTER_MIN_ANTAL_MAX, self::KLUSTER_MIN_ANTAL_STD, 'kluster.min_antal');
		$this->uppdatera_graf();
	}

	/**
	 * Håll minimumradie inom godtagbara intervall.
	 */
	private function min_radie(): void {
		$this->db_preferenser->validera_indata('min_radie', self::KLUSTER_MIN_RADIE_MIN, self::KLUSTER_MIN_RADIE_MAX, self::KLUSTER_MIN_RADIE_STD, 'kluster.min_radie');
		$this->uppdatera_graf();
	}

	/**
	 * Uppdatera graf.
	 */
	private function uppdatera_graf(): void {
		$spel = new Spel();
		$tips = new Tips($spel);
		$kluster = new Kluster($tips->utdelning, $tips->odds, $tips->streck, $tips->matcher);
		$kluster->finn_kluster();
		$tips->moduler->skapa_kombinationsgraf();
	}

	/**
	 * Spara attraktionsfaktor.
	 */
	private function attraktionsfaktor(): void {
		$this->ändra_attraktionsfaktor('kluster');
	}
}

new KlusterAjax();
