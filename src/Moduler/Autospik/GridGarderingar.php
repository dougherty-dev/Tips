<?php

/**
 * Klass GridGarderingar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\Autospik;

use Tips\Klasser\Utdelning;
use Tips\Klasser\Prediktioner;
use Tips\Klasser\Matcher;
use Tips\Klasser\DBPreferenser;

/**
 * Klass GridGarderingar.
 * Visa autogarderade matcher i fallande favoritskap.
 */
class GridGarderingar {
	use Konstanter;

	public DBPreferenser $db_preferenser;
	public int $valda_spikar = 0;
	/**
	 * @var int[] $spikar
	 */
	public array $spikar;
	/**
	 * @var int[] $teckenindex
	 */
	public array $teckenindex;
	/**
	 * @var int[] $oddsindex
	 */
	public array $oddsindex;

	/**
	 * Initiera.
	 */
	public function __construct(
		protected Utdelning $utdelning,
		protected Prediktioner $odds,
		protected Prediktioner $streck,
		protected Matcher $matcher
	) {
		$this->db_preferenser = new DBPreferenser($this->odds->spel->db);
	}

	/**
	 * Rendera garderingar.
	 */
	public function grid_garderingar(): string {
		/**
		 * Iterera över spikar.
		 */
		$tabell = '';
		foreach ($this->spikar as $i => $s) {
			$matchindex = $s + 1;
			$teckenutfall = siffror_till_symboler((string) $this->teckenindex[$i]);
			$valt_tecken = siffror_till_symboler((string) $this->oddsindex[$i]);

			$stil = match ($teckenutfall) {
				$valt_tecken => ' vinst',
				default => ' storförlust'
			};

			/**
			 * Bygg tabellrader.
			 */
			$tabell .= <<< EOT
							<tr>
								<td class="höger">$matchindex</td><td>{$this->matcher->match[$s]}</td>
								<td class="vinstrad">$valt_tecken</td><td class="vinstrad$stil">$teckenutfall</td>
							</tr>

EOT;
		}

		/**
		 * Skicka tillbaka tabell.
		 */
		return <<< EOT
						<p><select id="autospik">
{$this->generatorsträng(7)}						</select></p>
						<p>Autospika {$this->valda_spikar} matcher i topp.</p>
						<table>
							<tr class="match"><th>#</th><th>Match</th><th>Auto</th><th>T</th></tr>
$tabell						</table>
						<br>
EOT;
	}

	/**
	 * Gemensam sträng för rullgardinsmenyer.
	 */
	public function generatorsträng(int $indrag): string {
		/**
		 * Bygg selectelement.
		 */
		$generatorsträng = '';

		for ($i = self::AS_MIN; $i <= self::AS_MAX; $i++) {
			$vald = $this->valda_spikar === $i ? ' selected="selected"' : '';
			$generatorsträng .= t($indrag, "<option value=\"$i\"$vald>$i</option>");
		}

		return $generatorsträng;
	}
}
