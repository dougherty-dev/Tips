<?php

/**
 * Klass Autospik.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler;

use ReflectionClass;
use Tips\Klasser\Tips;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Egenskaper\Eka;
use Tips\Moduler\Autospik\Prova;

/**
 * Klass Autospik.
 */
final class Autospik extends Prova {
	use Eka;

	public string $tipsrader = '';
	public int $spikar_rätt = 0;
	/**
	 * @var string[] $tipsvektor
	 */
	public array $tipsvektor = [];

	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
		parent::__construct($utdelning, $odds, $streck, $matcher);
		$this->uppdatera_preferenser();
		$this->beräkna_spikar();
	}

	/**
	 * Visa modul.
	 */
	public function visa_modul(): void {
		$klass = (new ReflectionClass($this))->getShortName();

		echo <<< EOT
			<div id="modulflikar-$klass">
				<div class="autospik-grid">
					<div class="autospik-grid-garderingar">
						<h1>{$this->eka($this->utdelning->har_tipsrad && $this->pröva_tipsrad($this->utdelning->tipsrad_012) ? '✅' : '❌')} $klass</h1>
{$this->grid_garderingar()}
					</div> <!-- autospik-grid-garderingar -->
					<div class="autospik-grid-historik">
$this->historik					</div> <!-- autospik-grid-historik -->
				</div> <!-- autospik-grid -->
			</div> <!-- modulflikar-$klass -->

EOT;
	}

	/**
	 * Spara omgång.
	 * @SuppressWarnings("PHPMD.UnusedFormalParameter")
	 */
	public function spara_omgång(Tips $tips): void {
		$this->autospik_historik();
		$this->odds->spel->db->logg->logga(self::class . ': ✅ Sparade historik.');
	}
}
