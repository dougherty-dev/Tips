<?php

/**
 * Klass FANN.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler;

use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Moduler\FANNGenerera\Generera;
use Tips\Moduler\FANN\Konstanter;
use Tips\Moduler\FANN\Visa;

/**
 * Klass FANN.
 */
final class FANN extends Visa {
	use Konstanter;

	public string $indatafil = self::FANN . '/indata.txt';
	public string $utdatafil = self::FANN . '/utdata.txt';

	/**
	 * Initiera med uppdaterad konstruktor.
	 */
	public function __construct(
		public Utdelning $utdelning,
		public Prediktioner $odds,
		public Prediktioner $streck,
		public Matcher $matcher
	) {
		parent::__construct($utdelning, $odds, $streck, $matcher);
		$this->uppdatera_preferenser();
		$this->initiera_fann();
	}

	/**
	 * Starta upp FANN.
	 */
	private function initiera_fann(): void {
		if (isset($_REQUEST['generera_fann'])) {
			new Generera($this);
			$this->odds->spel->db->logg->logga(self::class . ': ✅ Genererade FANN.');
			$this->uppdatera_preferenser();
		}

		if ($this->exists_fann = file_exists($this->utdatafil)) {
			$this->fann = fann_create_from_file($this->utdatafil);
		}

		$this->prediktera();

		if (isset($_REQUEST['generera'])) { // preparera för parallellisering
			$this->db_preferenser->spara_preferens('fann.utdata', implode(',', $this->utdata), 'temp');
		}

		// parallellisering, nyttja befintlig prediktion för omgång
		$this->utdata = explode(',', $this->db_preferenser->hämta_preferens('fann.utdata', 'temp'));
	}
}
