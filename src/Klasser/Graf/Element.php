<?php

/**
 * Klass Element.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Graf;

/**
 * Klass Element.
 */
class Element {
	public \GdImage $graf;
	public int $bredd;
	public int $höjd;
	public int $gul;
	public int $röd;
	public int $grön;
	public int $blå;
	public int $lila;
	public int $vit;
	public int $svart;
	/**
	 * @var int[] $gul_v
	 */
	public array $gul_v;
	/**
	 * @var int[] $röd_v
	 */
	public array $röd_v;
	/**
	 * @var int[] $grön_v
	 */
	public array $grön_v;

	/**
	 * 3^13 = 3^6*3^7 = 729 * 2187 = 1,594,323
	 */
	public function __construct(int $bredd = 2187, int $höjd = 729) {
		$this->bredd = $bredd;
		$this->höjd = $höjd;
	}

	/**
	 * Rendera enskild pixel i koordinat x, y med färg.
	 */
	public function sätt_pixel(int $x, int $y, int $färg): void {
		imagesetpixel($this->graf, $x, $y, $färg);
	}

	/**
	 * Rendera rektangel.
	 */
	public function sätt_rektangel(int $x1, int $y1, int $x2, int $y2, int $färg): void {
		[$x1, $y1] = [max(0, $x1), max(0, $y1)];
		[$x2, $y2] = [min($x2, $this->bredd), min($y2, $this->höjd)];
		imagerectangle($this->graf, $x1, $y1, $x2, $y2, $färg);
	}

	/**
	 * Rendera fylld rektangel.
	 */
	public function fyll_rektangel(int $x1, int $y1, int $x2, int $y2, int $färg): void {
		[$x1, $y1] = [max(0, $x1), max(0, $y1)];
		[$x2, $y2] = [min($x2, $this->bredd), min($y2, $this->höjd)];
		imagefilledrectangle($this->graf, $x1, $y1, $x2, $y2, $färg);
	}

	/**
	 * Rendera linje.
	 */
	public function sätt_linje(int $x1, int $y1, int $x2, int $y2, int $färg): void {
		imageline($this->graf, $x1, $y1, $x2, $y2, $färg);
	}

	/**
	 * Rendera cirkel.
	 */
	public function sätt_cirkel(int $x, int $y, int $diam, int $färg): void {
		imageellipse($this->graf, $x, $y, $diam, $diam, $färg);
	}

	/**
	 * Rendera text.
	 */
	public function sätt_text(int $x, int $y, string $text, int $färg, int $typ = 5): void {
		imagestring($this->graf, $typ, $x, $y, $text, $färg);
	}
}
