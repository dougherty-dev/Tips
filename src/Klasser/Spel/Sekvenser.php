<?php

/**
 * Klass Sekvenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Spel;

use PDO;
use Tips\Klasser\Databas;
use Tips\Klasser\Filer;
use Tips\Klasser\Speltyp;

/**
 * Klass Sekvenser.
 */
class Sekvenser {
	public Databas $db;
	public Filer $filer;
	public Speltyp $speltyp;
	public bool $spel_finns = false;
	public int $omgång = -1;
	public int $sekvens = -1;
	/**
	 * @var int[] $sekvenser
	 */
	public array $sekvenser = [];

	/**
	 * Init.
	 */
	public function __construct() {
		$this->db = new Databas();
		$this->filer = new Filer($this->db);
	}

	/**
	 * Hämta sekvenser.
	 */
	public function hämta_sekvenser(): void {
		$this->sekvenser = [];
		$this->spel_finns = true; // vi har $this->omgång, $this->speltyp
		$sats = $this->db->instans->prepare("SELECT `sekvens` FROM `spel` WHERE `omgång`=:omgang AND `speltyp`=:speltyp");
		$sats->bindValue(':omgang', $this->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->speltyp->value, PDO::PARAM_INT);
		$sats->bindColumn('sekvens', $sekvens, PDO::PARAM_INT);
		$sats->execute();
		while ($sats->fetch(PDO::FETCH_OBJ)) {
			$this->sekvenser[] = $sekvens;
		}

		if (count($this->sekvenser)) {
			if (!in_array($this->sekvens, $this->sekvenser, true)) {
				$this->sekvens = max($this->sekvenser);
			}

			return;
		}

		$this->sekvens = 1;
		$this->sekvenser[] = $this->sekvens;
	}
}
