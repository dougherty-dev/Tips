<?php

/**
 * Klass Hamta.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Scheman;

use PDO;
use Tips\Klasser\Tips;

/**
 * Klass Hamta.
 */
class Hamta extends Mallar {
	/** @var array<int, array<string, string>> $scheman */ public array $scheman = [];

	public function __construct(protected Tips $tips) {
	}

	/**
	 * Hämta scheman.
	 */
	protected function hämta_scheman(): void {
		$sats = $this->tips->odds->spel->db->instans->prepare('SELECT * FROM `scheman`');
		$sats->execute();

		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$this->scheman[(int) $row['id']] = [
				'data' => (string) $row['data'],
				'faktorer' => (string) $row['faktorer']
			];
		}
	}
}
