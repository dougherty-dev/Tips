<?php

/**
 * Klass SparaSchemaAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use PDO;
use Tips\Klasser\Preludium;
use Tips\Klasser\Spel;
use Tips\Klasser\Tips;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass SparaSchemaAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class SparaSchemaAjax extends PackaData {
	use Ajax;

	private Spel $spel;
	private Tips $tips;

	/**
	 * Inititiera.
	 */
	public function __construct() {
		$this->spel = new Spel();
		$this->tips = new Tips($this->spel);
		$this->tips->moduler->annonsera_moduler();
		$this->förgrena();
	}

	/**
	 * Spara nytt schema.
	 * js/funktioner.js: spara_nytt_schema
	 */
	private function spara_nytt_schema(): void {
		[$data, $moduldata] = $this->packa_data('spara_nytt_schema');

		$sats = $this->spel->db->instans->prepare("INSERT INTO `scheman`
			(`data`, `faktorer`) VALUES (:data, :faktorer)");
		$sats->bindValue(':data', implode(',', $data), PDO::PARAM_STR);
		$sats->bindValue(':faktorer', implode(',', $moduldata), PDO::PARAM_STR);
		$sats->execute();
	}

	/**
	 * Uppdatera schema.
	 * js/funktioner.js: uppdatera_schema, id
	 */
	private function uppdatera_schema(): void {
		$id = (int) filter_var($_REQUEST['id'], FILTER_VALIDATE_INT);

		[$data, $moduldata] = $this->packa_data('uppdatera_schema');

		$sats = $this->spel->db->instans->prepare("REPLACE INTO `scheman` (`id`, `data`, `faktorer`)
			VALUES (:id, :data, :faktorer)");
		$sats->bindValue(':id', $id, PDO::PARAM_INT);
		$sats->bindValue(':data', implode(',', $data), PDO::PARAM_STR);
		$sats->bindValue(':faktorer', implode(',', $moduldata), PDO::PARAM_STR);
		echo $sats->execute();
	}

	/**
	 * Radera schema.
	 * js/funktioner.js: radera_schema
	 */
	private function radera_schema(): void {
		$id = (int) filter_var($_REQUEST['radera_schema'], FILTER_VALIDATE_INT);

		$sats = $this->spel->db->instans->prepare("DELETE FROM `scheman` WHERE `id`=:id");
		$sats->bindValue(':id', $id, PDO::PARAM_INT);
		$sats->execute();
	}
}

new SparaSchemaAjax();
