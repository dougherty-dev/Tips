<?php

/**
 * Klass RaderAjax.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Ajax;

use Tips\Klasser\Preludium;
use Tips\Egenskaper\Ajax;

/**
 * Ajaxanrop ligger utanför ordinarie ordning.
 */
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
new Preludium();

/**
 * Klass RaderAjax.
 * @SuppressWarnings("PHPMD.UnusedPrivateMethod")
 */
final class RaderAjax {
	use Ajax;

	/**
	 * Inititiera.
	 */
	public function __construct() {
		$this->förgrena();
	}

	/**
	 * Hämta fil med sparade rader.
	 * klasser/Spelade: visa_genererade_rader()
	 */
	private function fil(): void {
		$fil = is_string($_REQUEST['fil']) ? base64_decode($_REQUEST['fil'], true) : false;
		if ($fil !== false) {
			echo file_get_contents(BAS . GENERERADE . htmlspecialchars($fil));
		}
	}
}

new RaderAjax();
