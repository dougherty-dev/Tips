<?php

/**
 * Klass Sekvenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

/**
 * Klass Sekvenser.
 * Upprepa procedurer för varje lagt spel.
 */
final class Sekvenser {
	public function __construct(private Spel $spel) {
	}

	/**
	 * Traversera sekvenser.
	 */
	public function traversera_sekvenser(): void {
		$ursprunglig_sekvens = $this->spel->sekvens;

		foreach ($this->spel->sekvenser as $sekvens) {
			$this->spel->sekvens = $sekvens;
			$this->spel->spara_spel();
			$tips = new Tips($this->spel);

			match (true) {
				$ursprunglig_sekvens === $sekvens => $this->spara_modulomgång($tips),
				default => $this->spara_distomgång($tips)
			};

			/**
			 * js/funktioner.js: spara_matchdata SEKUNDÄR
			 * Generera en tipsgrad för varje enskilt spel i omgång (sekvens).
			 */
			if (isset($_REQUEST['spara_matchdata'])) {
				$tips->spelade->generera_tipsgraf();
			}

			/**
			 * js/funktioner.js: hämta_tips SEKUNDÄR
			 * Generera en tipsgrad för varje enskilt spel i omgång (sekvens).
			 * Uppdatera investeringsinformation.
			 */
			if (isset($_REQUEST['hämta_tips']) && $_REQUEST['hämta_tips'] === 'hämta_tipsresultat') {
				$tips->spelade->generera_tipsgraf();
				(new Investera($tips))->investera();
			}
		}

		$this->spel->sekvens = $ursprunglig_sekvens;
		$this->spel->spara_spel();
	}

	/**
	 * Spara data för varje tillämplig modul.
	 */
	private function spara_modulomgång(Tips $tips): void {
		foreach ($tips->moduler->m_moduler as $modul) {
			if (method_exists($modul, 'spara_omgång')) {
				$modul->spara_omgång($tips);
			}
		}
	}

	/**
	 * Spara bara data för modul Distribution.
	 * Kan göras smidigare med in_array?
	 */
	private function spara_distomgång(Tips $tips): void {
		foreach ($tips->moduler->m_moduler as $modul) {
			if (get_class($modul) == 'Distribution' && method_exists($modul, 'spara_omgång')) {
				$modul->spara_omgång($tips);
			};
		}
	}
}
