<?php

/**
 * Klass Hamta.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Investera;

use PDO;
use Tips\Klasser\Tips;
use Tips\Klasser\DBPreferenser;

/**
 * Klass Hamta.
 * Hämta investeringsdata för enskild omgång.
 */
class Hamta {
	use Konstanter;

	protected bool $spelad = false;
	protected int $visa_antal = 20;
	protected int $antal_investeringar = 0;
	protected int $antal_genererade = 0;
	protected int $antal_utvalda_rader = 0;
	protected int $vinst = 0;
	protected string $tid = '';
	/**
	 * @var int[] $vinstdata
	 */
	protected array $vinstdata = [];

	/**
	 * Initiera.
	 */
	public function __construct(protected Tips $tips) {
	}

	/**
	 * Uppdatera preferenser.
	 * Kontrollera gränser.
	 */
	protected function uppdatera_preferenser(): void {
		(new DBPreferenser($this->tips->spel->db))->int_preferens_i_intervall(
			$this->visa_antal,
			self::INVESTERA_VISA_MIN,
			self::INVESTERA_VISA_MAX,
			self::INVESTERA_VISA_STD,
			'invest.visa_antal'
		);
	}

	/**
	 * Hämta investering.
	 */
	protected function hämta_investering(): void {
		$this->vinstdata = TOM_VINSTMATRIS;

		/**
		 * Hämta enskild investering för aktuell omgång, speltyp och sekvens.
		 */
		$sats = $this->tips->spel->db->instans->prepare(
			"SELECT `genererade`, `valda`, `vinst`, `tid`, `u10`, `u11`, `u12`, `u13`
			FROM `investerade` WHERE `omgång`=:omgang
			AND `speltyp`=:speltyp AND `sekvens`=:sekvens LIMIT 1"
		);
		$sats->bindValue(':omgang', $this->tips->utdelning->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->tips->utdelning->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->tips->utdelning->spel->sekvens, PDO::PARAM_INT);
		$sats->bindColumn('genererade', $this->antal_genererade, PDO::PARAM_INT);
		$sats->bindColumn('valda', $this->antal_utvalda_rader, PDO::PARAM_INT);
		$sats->bindColumn('vinst', $this->vinst, PDO::PARAM_INT);
		$sats->bindColumn('tid', $this->tid, PDO::PARAM_STR);
		$sats->bindColumn('u10', $this->vinstdata[10], PDO::PARAM_INT);
		$sats->bindColumn('u11', $this->vinstdata[11], PDO::PARAM_INT);
		$sats->bindColumn('u12', $this->vinstdata[12], PDO::PARAM_INT);
		$sats->bindColumn('u13', $this->vinstdata[13], PDO::PARAM_INT);
		$sats->execute();

		$this->spelad = count($sats->fetchAll(PDO::FETCH_ASSOC)) > 0;
	}

	/**
	 * Hämta totalt antal gjorda investeringar.
	 */
	protected function antal_investeringar(): int {
		$sats = $this->tips->spel->db->instans->prepare("SELECT COUNT(`omgång`) FROM `investerade`");
		$sats->execute();
		$this->antal_investeringar = (int) $sats->fetchColumn();
		$sats->closeCursor(); // Egenhet för sqlite

		return $this->antal_investeringar;
	}
}
