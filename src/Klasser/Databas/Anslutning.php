<?php

/**
 * Klass Anslutning.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Databas;

use PDO;
use Tips\Klasser\Logg;

/**
 * Klass Anslutning.
 */
class Anslutning {
	public PDO $instans;
	public PDO $temp;
	public Logg $logg;

	/**
	 * Anslut vid instantiering.
	 */
	public function __construct() {
		$this->anslut();
		$this->logg = new Logg($this->temp);
	}

	/**
	 * Anslut till DB.
	 */
	public function anslut(): void {
		$this->instans = new PDO('sqlite:' . DB . '/tips.db');
		$this->instans->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->instans->setAttribute(PDO::ATTR_PERSISTENT, true);
		$this->temp = new PDO('sqlite:' . DB . '/temp.db');
		$pragma = 'PRAGMA temp_store = MEMORY; PRAGMA mmap_size = 1000000000; PRAGMA auto_vacuum = FULL; PRAGMA busy_timeout = 5000';
		$this->instans->exec($pragma);
		$this->temp->exec($pragma);
	}
}
