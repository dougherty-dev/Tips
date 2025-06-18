<?php

/**
 * Klass FANNGenerera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANNGenerera;

use Tips\Moduler\FANN;

/**
 * Klass FANNGenerera.
 */
final class Generera extends Fordelning {
	/**
	 * Uppdatera konstruktor.
	 */
	public function __construct(public FANN $fann) {
		parent::__construct($this->fann);
		$this->träna_fann();
	}

	/**
	 * Träna neuralt nätverk på befintliga data.
	 */
	private function träna_fann(): void {
		$this->hämta_värden($this->fann->odds->spel->db);
		$this->generera_träningsdata();

		$lager = [$this->neuroner_in, $this->neuroner_ut];
		$this->antal_lager = count($lager);
		$this->fann->fann = fann_create_shortcut_array($this->antal_lager, $lager);

		/**
		 * Aktiveringsfunktioner och träningsalgoritm.
		 */
		fann_set_activation_function_hidden($this->fann->fann, FANN_SIGMOID_SYMMETRIC_STEPWISE);
		fann_set_activation_function_output($this->fann->fann, FANN_SIGMOID_SYMMETRIC_STEPWISE);
		fann_set_training_algorithm($this->fann->fann, FANN_TRAIN_RPROP);

		$this->exists_fann = fann_cascadetrain_on_file($this->fann->fann, $this->fann->indatafil, $this->max_neuroner, 0, $this->fann->feltolerans);

		$logg = "Kunde inte träna FANN.<br>";
		if ($this->exists_fann) {
			fann_save($this->fann->fann, $this->fann->utdatafil);
			unlink($this->fann->indatafil);
			$logg = 'MSE: ' . fann_get_mse($this->fann->fann) . '<br>';
		}
		$this->fann->logg .= $logg;

		$this->fann->db_preferenser->spara_preferens('fann.status', $this->fann->logg);

		$this->generera_parametrar();
		$this->beräkna_fannfördelning();
	}
}
