<?php

/**
 * Klass Data.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANN;

use Tips\Egenskaper\Eka;

/**
 * Klass Data.
 */
class Data extends Omgang {
	use Eka;

	public string $logg = '';

	/**
	 * Data för neuralt nätverk.
	 * Kontroll för att generera ett nytt nätverk med parametrar.
	 */
	protected function fanndata(): string {
		$fanndata = '';
		$this->logg = $this->db_preferenser->hämta_preferens('fann.status');

		/**
		 * Rendera data om FANN-anslutning.
		 */
		if ($this->exists_fann) {
			$fanndata = <<< EOT
						<div id="fannlogg"><em>{$this->logg}</em></div>
						<div>
							<p>FANN <strong>{$this->eka((string) phpversion("fann"))}</strong>:</p>
							<p>Nätverkstyp: {$this->eka((string) fann_get_network_type($this->fann))}<br>
							In: {$this->eka((string) fann_get_num_input($this->fann))}<br>
							Ut: {$this->eka((string) fann_get_num_output($this->fann))}<br>
							Lager: {$this->eka((string) fann_get_num_layers($this->fann))}<br>
							Anslutningar: {$this->eka((string) fann_get_total_connections($this->fann))}<br>
							Neuroner: {$this->eka((string) fann_get_total_neurons($this->fann))}<br>
						</div>
						<form method="post" action="/#modulflikar-FANN">
							<p><button name="generera_fann">Träna FANN</button></p>
						</form>
EOT;
		}

		return $fanndata;
	}
}
