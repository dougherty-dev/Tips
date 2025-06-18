<?php

/**
 * Klass Bokforing.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Ajax\TTAjax;

/**
 * Klass Bokforing.
 */
class Bokforing extends Matchdata {
	/**
	 * Radera bokföringspost för Topptipset.
	 */
	protected function tt_radera_bokföring(): void {
		$id = is_numeric($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

		$sats = $this->db->instans->prepare("DELETE FROM `TT_bokföring` WHERE `id`=$id");
		$kommentar = match ($sats->execute()) {
			true => ": ✅ Raderade TT-bokföring.",
			false => ": ❌ Kunde inte radera TT-bokföring."
		};
		$this->db->logg->logga(self::class . "$kommentar ($id)");
	}

	/**
	 * Spara antal bokföringsposter att visa för Topptipset.
	 * Normalt 5.
	 */
	protected function tt_visa_antal_bokföringar(): void {
		$this->db_preferenser->spara_preferens(
			"topptips.visa_antal_bokf",
			(string) filter_var($_REQUEST['tt_visa_antal_bokföringar'], FILTER_VALIDATE_INT)
		);
	}

	/**
	 * Uppdatera bokföring för Topptipset.
	 */
	protected function tt_uppdatera_bokföring(): void {
		$id = is_numeric($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

		$värde = is_numeric($_REQUEST['värde']) ? intval($_REQUEST['värde']) : 0;
		$kolumn = filter_var($_REQUEST['kolumn'], FILTER_SANITIZE_SPECIAL_CHARS);

		$sats = $this->db->instans->prepare("UPDATE `TT_bokföring` SET `$kolumn`=$värde WHERE `id`=$id");
		$kommentar = match ($sats->execute()) {
			true => ": ✅ Uppdaterade TT-bokföring.",
			false => ": ❌ Kunde inte uppdatera TT-bokföring."
		};
		$this->db->logg->logga(self::class . "$kommentar ($id $kolumn $värde)");
	}
}
