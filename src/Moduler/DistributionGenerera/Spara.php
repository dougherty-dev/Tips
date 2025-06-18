<?php

/**
 * Klass Spara.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\DistributionGenerera;

use PDO;
use Tips\Klasser\Tips;
use Tips\Moduler\Distribution;

/**
 * Klass Spara.
 */
class Spara {
	/**
	 * @var bool[] $andel_vid
	 */
	protected array $andel_vid;

	public function __construct(protected Tips $tips, protected Distribution $dist) {
		$this->andel_vid = array_fill_keys(['10', '5', '3', '2', '1', '0.5'], false);
	}

	/**
	 * Spara distribution.
	 */
	protected function spara_distribution(): void {
		$sats = $this->tips->odds->spel->db->instans->prepare("REPLACE INTO `distribution`
			(`omgång`, `speltyp`, `sekvens`, `minsumma`, `maxsumma`, `minprocent`, `maxprocent`,
			`oddssumma`, `procentandel`, `andelssumma`) VALUES
			(:omgang, :speltyp, :sekvens, :minsumma, :maxsumma, :minprocent, :maxprocent,
			:oddssumma, :procentandel, :andelssumma)");
		$sats->bindValue(':omgang', $this->tips->odds->spel->omgång, PDO::PARAM_INT);
		$sats->bindValue(':speltyp', $this->tips->odds->spel->speltyp->value, PDO::PARAM_INT);
		$sats->bindValue(':sekvens', $this->tips->odds->spel->sekvens, PDO::PARAM_INT);
		$sats->bindValue(':minsumma', $this->dist->minsumma, PDO::PARAM_STR);
		$sats->bindValue(':maxsumma', $this->dist->maxsumma, PDO::PARAM_STR);
		$sats->bindValue(':minprocent', $this->dist->minprocent, PDO::PARAM_STR);
		$sats->bindValue(':maxprocent', $this->dist->maxprocent, PDO::PARAM_STR);
		$sats->bindValue(':oddssumma', $this->dist->oddssumma, PDO::PARAM_STR);
		$sats->bindValue(':procentandel', $this->dist->procentandel, PDO::PARAM_STR);
		$sats->bindValue(':andelssumma', $this->dist->andelssumma, PDO::PARAM_INT);
		$sats->execute();
	}
}
