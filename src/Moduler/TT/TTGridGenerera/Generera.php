<?php

/**
 * Klass Generera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT\TTGridGenerera;

use Tips\Egenskaper\Eka;
use Tips\Egenskaper\Ajax;
use Tips\Moduler\TT;

/**
 * Klass Generera.
 */
final class Generera extends Tackning {
	use Eka;
	use Ajax;

	/**
	 * Förgrena ajax-anrop.
	 */
	public function __construct(protected TT $tt) {
		$this->förgrena();
	}

	/**
	 * Genererea TT-rader.
	 * $_REQUEST['generera_topptips']
	 */
	public function generera_topptips(): void {
		$this->täckningskod();
		$this->generera();

		array_map(fn (string $tipsrad_012): null => $this->rita($tipsrad_012, $this->tt->graf->grön_v[1]), $this->tt::TT_TOPPTIPSRADER);

		array_map(fn (string $tipsrad_012): null => $this->rita($tipsrad_012, $this->tt->graf->gul), $this->tt->rader);

		$this->tt->db_preferenser->spara_preferens('topptips.genererade_rader', strval(count($this->tt->rader)));
		$this->tt->rader = array_slice($this->tt->rader, 0, $this->tt->antal_rader);
		sort($this->tt->rader);

		$radretur = "{$this->tt->typer['externa']},Omg={$this->tt->omgång},Insats=1\r\n" .
			implode("\r\n", array_map(fn (string $rad): string => "E," . kommatisera($rad), $this->tt->rader));

		is_dir($this->tt::TT_MAPP) or mkdir($this->tt::TT_MAPP, 0770, true);
		$kommentar = match (file_put_contents($this->tt::TT_TEXTFIL, $radretur) > 0) {
			true => ": ✅ Sparade",
			false => ": ❌ Kunde inte spara"
		};
		$this->tt->utdelning->spel->db->logg->logga(self::class . "$kommentar {$this->eka($this->tt::TT_TEXTFIL)}. ({$this->tt->omgång})");

		array_map(fn (string $tipsrad_012): null => $this->rita($tipsrad_012, $this->tt->graf->röd), $this->tt->rader);
		$this->tt->graf->spara_tipsgraf($this->tt::TT_BILDFIL);
	}
}
