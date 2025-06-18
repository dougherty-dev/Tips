<?php

/**
 * Klass Logg.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use PDO;

/**
 * Klass Logg.
 */
final class Logg {
	/**
	 * Anslut vid instantiering.
	 */
	public function __construct(public PDO $temp) {
	}

	/**
	 * Logga händelse.
	 */
	public function logga(string $notering): void {
		$sats = $this->temp->prepare("INSERT INTO `logg` (`notering`) VALUES (:notering)");
		$sats->bindValue(':notering', (string) filter_var($notering, FILTER_SANITIZE_SPECIAL_CHARS), PDO::PARAM_STR);
		$sats->execute();
	}

	/**
	 * Hämta logg.
	 */
	public function hämta_logg(): string {
		$this->logga(self::class . ': -------------------- START --------------------');
		$this->temp->exec('DELETE FROM `logg` WHERE `id` < (SELECT `id` FROM `logg` ORDER BY `id` DESC LIMIT 1 OFFSET 98)');
		$text = '';
		$sats = $this->temp->prepare("SELECT datetime(`datum`, 'localtime') AS `lokalt_datum`, `notering` FROM `logg` ORDER BY `id` DESC");
		$sats->execute();
		foreach ($sats->fetchAll(PDO::FETCH_ASSOC) as $r) {
			$text .= t(6, "{$r['lokalt_datum']}: {$r['notering']}<br>");
		}
		return rtrim($text);
	}
}
