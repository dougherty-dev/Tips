<?php

/**
 * Klass Fordelning.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANNGenerera;

/**
 * Klass Fordelning.
 * Ger antal, andel och ackumulerad andel rätt över alla omgångar.
 * 11 r motsvarar ungefär 31 % av alla rader, 10 r 62 %.
 */
class Fordelning extends Ratt {
	/**
	 * Beräkna fördelning för FANN.
	 */
	protected function beräkna_fannfördelning(): void {
		/**
		 * Kräv anslutning till FANN.
		 */
		if (!$this->exists_fann) {
			return;
		}

		/**
		 * Tabellhuvud
		 */
		$antal_rader = count($this->oddssannolikheter);
		$ackumulerat = 0;
		$fördelningstabell = <<< EOT
						<table id="FANN-fördelning">
							<tr>
								<th>Rätt</th>
								<th>Antal</th>
								<th>% / $antal_rader</th>
								<th>Ack. %</th>
							</tr>

EOT;

		/**
		 * Iterera över statistik för FANN.
		 */
		foreach ($this->beräkna_fannrätt() as $rätt => $antal) {
			$ackumulerat += $procent = $antal / $antal_rader;
			$ackstil = stil(1 - $ackumulerat / 100);
			$procentandel = number_format(100 * $procent, 2);
			$ackumulerad_andel = number_format(100 * $ackumulerat, 2);

			/**
			 * Bygg tabell
			 */
			$fördelningstabell .= <<< EOT
							<tr class="höger">
								<td>$rätt</td>
								<td>$antal</td>
								<td>$procentandel</td>
								<td$ackstil>$ackumulerad_andel</td>
							</tr>

EOT;
		}

		/**
		 * Spara och logga.
		 */
		$fördelningstabell .= t(6, "</table>");
		$this->fann->db_preferenser->spara_preferens('fann.fördelning', $fördelningstabell);
		$this->fann->odds->spel->db->logg->logga(self::class . ' ✅ Sparade fördelningstabell.');
	}
}
