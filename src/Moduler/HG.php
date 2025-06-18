<?php

/**
 * Klass HG.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler;

use ReflectionClass;
use Tips\Klasser\Tips;
use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\DBPreferenser;
use Tips\Egenskaper\Eka;
use Tips\Moduler\HG\Omgang;

/**
 * Klass HG.
 * Mest sannolika rad av halvgarderingar.
 * 11 rätt täcker 43 % av matcher, 10 rätt 68 %.
 * Högre vinster motsvarar lägre intervall.
 */
final class HG extends Omgang {
	use Eka;

	/**
	 * Initiera.
	 */
	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
		parent::__construct($utdelning, $odds, $streck, $matcher);
		$this->db_preferenser = new DBPreferenser($this->odds->spel->db);
		$this->uppdatera_preferenser();
		$this->utdata = $this->beräkna_utdata($this->odds->sannolikheter);
	}

	/**
	 * Visa modul.
	 */
	public function visa_modul(): void {
		$klass = (new ReflectionClass($this))->getShortName();

		/**
		 * Säkerställ fördelning.
		 */
		$this->fördelning = $this->db_preferenser->hämta_preferens('hg.fördelning');
		if ($this->fördelning === '') {
			$this->beräkna_fördelning();
		}

		/**
		 * Presentera modulsida.
		 */
		echo <<< EOT
			<div id="modulflikar-$klass">
				<div class="hg-övre-grid">
					<div class="hg-grid-omgång">
						<h1>{$this->eka($this->utdelning->har_tipsrad && $this->pröva_tipsrad($this->utdelning->tipsrad_012) ? '✅' : '❌')} $klass</h1>
						<p><select id="hg_min">
{$this->generatorsträng(7)}							</select></p>
{$this->hg_omgång()}
					</div> <!-- hg-grid-omgång -->
				</div> <!-- hg-övre-grid -->
				<div class="hg-nedre-grid">
					<div class="hg-grid-fördelning">
{$this->fördelning}
					</div> <!-- hg-grid-fördelning -->
				</div> <!-- hg-nedre-grid -->
			</div> <!-- modulflikar-$klass -->

EOT;
	}

	/**
	 * Spara omgång.
	 * @SuppressWarnings("PHPMD.UnusedFormalParameter")
	 */
	public function spara_omgång(Tips $tips): void {
		$this->beräkna_fördelning();
		$this->odds->spel->db->logg->logga(self::class . ': ✅ Sparade fördelning.');
	}
}
