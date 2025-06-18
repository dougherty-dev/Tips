<?php

/**
 * Enum TTKod.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\TT;

use Tips\Egenskaper\Kod;

/**
 * Enum TTKod.
 */
enum TTKod: string {
	use Kod;

	case BCH_8_6_2_3 = 'BCH_8_6_2_3';
	case GRUPPKOD_8_3_3 = 'GRUPPKOD_8_3_3';

	/**
	 * Antal rader för kod.
	 */
	public function antal_rader(): string {
		return match ($this) {
			static::BCH_8_6_2_3 => '729',
			static::GRUPPKOD_8_3_3 => '567'
		};
	}

	/**
	 * Täckning för kod.
	 */
	public function täckning(): string {
		return match ($this) {
			static::BCH_8_6_2_3 => '1',
			static::GRUPPKOD_8_3_3 => '1'
		};
	}

	/**
	 * Koddata för kod.
	 */
	public function koddata(): string {
		return $this->{$this->name}();
	}

	/**
	 * Generatormatris för BCH_8_6_2_3.
	 */
	public function BCH_8_6_2_3(): string {
		$generatormatris = [
			[1, 0, 0, 0, 0, 0, 2, 2],
			[0, 1, 0, 0, 0, 0, 2, 1],
			[0, 0, 1, 0, 0, 0, 1, 0],
			[0, 0, 0, 1, 0, 0, 0, 1],
			[0, 0, 0, 0, 1, 0, 1, 1],
			[0, 0, 0, 0, 0, 1, 1, 2]
		];

		$kontrollmatris = [
			[1, 0, 1, 1, 2, 0, 2, 2],
			[0, 1, 1, 2, 0, 2, 2, 1]
		];

		$matris = [
			[1, 1, 2, 0, 2, 2],
			[1, 2, 0, 2, 2, 1]
		];

		return $this->visa_kodinformation(
			$generatormatris,
			$kontrollmatris,
			$matris,
			[8, 6, 2, 3],
			7,
			[
				'Kod',
				'1',
				__FUNCTION__,
				'Cyklisk',
				'BCH ',
				'BCHCode(8, 2, GF(3))'
			],
			'[1, 0, 8, 64, 120, 176, 232, 96, 32]'
		);
	}

	/**
	 * Algoritm för GRUPPKOD_8_3_3.
	 */
	public function GRUPPKOD_8_3_3(): string {
		return <<< EOT
						<h2>Kod:</h2>
						<p class="större">GRUPPKOD_8_3_3<br>
							Gruppkod [8, 3]₃ över 𝔽₃<br>
							M = 9 · (3 · 3 + 54) = 567; r = 1<br>
							Rymd: 6 561; reduktion: 11.57</p>
EOT;
	}
}
