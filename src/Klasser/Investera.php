<?php

/**
 * Klass Investera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use PDO;
use Tips\Klasser\Investera\Visa;

/**
 * Klass Investera.
 * Hanterar lagda spel på stryk- och europatips.
 * Tabell med vinster och utgifter samt motsvarande graf.
 */
final class Investera extends Visa {
	/**
	 * Hämta investeringsdata. Uppdatera.
	 * Spara i databas.
	 * Hantera grafer.
	 */
	public function investera(): void {
		/**
		 * Finns spelade rader?
		 */
		if ($this->tips->spelade->antal_utvalda_rader === 0) {
			return;
		}

		/**
		 * Hämta befintliga data.
		 * Om inga data finns föreligger ny post.
		 */
		$tid = date("Y-m-d H:i:s"); // defaulttid
		$this->vinstdata = TOM_VINSTMATRIS; // sätt default till tom
		$sats = $this->tips->spel->db->instans->prepare("SELECT `tid` FROM `investerade`
			WHERE `omgång`=:omgang AND `speltyp`=:speltyp AND `sekvens`=:sekvens LIMIT 1");
		$sats->bindValue(':omgang', $this->tips->utdelning->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->tips->utdelning->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->tips->utdelning->spel->sekvens, PDO::PARAM_INT);
		$sats->bindColumn('tid', $tid, PDO::PARAM_STR);
		$sats->execute();
		$sats->fetchColumn();
		$sats->closeCursor();

		/**
		 * Är omgången avgjord?
		 * Radera tidigare grafer.
		 */
		if ($this->tips->utdelning->tipsrad_012 !== '') {
			$this->radera_graf($this->fil);
			$this->radera_graf($this->fil_ack);

			/**
			 * Plussa på vinstdata.
			 */
			foreach ($this->tips->spelade->tipsvektor as $tipsrad_012) {
				$rätt = antal_rätt($tipsrad_012, $this->tips->utdelning->tipsrad_012);
				if ($rätt >= 10) {
					$this->vinstdata[$rätt]++;
				}
			}
		}

		/**
		 * Slå samman vinstsumman.
		 */
		$vinst = vektorprodukt(array_reverse($this->vinstdata), $this->tips->utdelning->utdelning);

		/**
		 * Delete och insert av investdata.
		 * Inget behov av att kolla existens.
		 */
		$sats = $this->tips->spel->db->instans->prepare("REPLACE INTO `investerade`
			(`omgång`, `speltyp`, `sekvens`, `genererade`, `valda`, `vinst`, `tid`, `u10`, `u11`, `u12`, `u13`)
			VALUES (:omgang, :speltyp, :sekvens, :genererade, :valda, :vinst, :tid, :u10, :u11, :u12, :u13)");
		$sats->bindValue(':omgang', $this->tips->utdelning->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->tips->utdelning->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->tips->utdelning->spel->sekvens, PDO::PARAM_INT);
		$sats->bindValue(':genererade', $this->tips->spelade->antal_genererade, PDO::PARAM_INT);
		$sats->bindValue(':valda', $this->tips->spelade->antal_utvalda_rader, PDO::PARAM_INT);
		$sats->bindValue(':vinst', $vinst, PDO::PARAM_INT);
		$sats->bindValue(':tid', $tid, PDO::PARAM_STR);
		$sats->bindValue(':u10', $this->vinstdata[10], PDO::PARAM_INT);
		$sats->bindValue(':u11', $this->vinstdata[11], PDO::PARAM_INT);
		$sats->bindValue(':u12', $this->vinstdata[12], PDO::PARAM_INT);
		$sats->bindValue(':u13', $this->vinstdata[13], PDO::PARAM_INT);
		$sats->execute();

		/**
		 * Logga händelse.
		 */
		$this->tips->spel->db->logg->logga(self::class . ": ✅ Investerade. ({$this->tips->utdelning->spel->omgång}, {$this->tips->utdelning->spel->sekvens})");
	}

	/**
	 * Radera graf om sådan finns.
	 */
	private function radera_graf(string $fil): void {
		if (is_file(GRAF . $fil)) {
			unlink(GRAF . $fil);
		}
	}
}
