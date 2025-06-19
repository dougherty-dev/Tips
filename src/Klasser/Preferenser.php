<?php

/**
 * Klass Preferenser.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser;

use SQLite3;
use Tips\Egenskaper\Varden;
use Tips\Egenskaper\Eka;

/**
 * Klass Preferenser.
 * Flik med diverse inställningar.
 */
final class Preferenser {
	use Varden;
	use Eka;

	/**
	 * Initiera.
	 */
	public function __construct(public Databas $db) {
		$this->hämta_värden($this->db);
		$this->trådar = in_array($this->trådar, TRÅDMÄNGD, true) ? $this->trådar : 9;
		$this->visa_preferenser();
	}

	/**
	 * Visa preferenser.
	 */
	private function visa_preferenser(): void {
		$databaser = '';

		/**
		 * Info om databaser.
		 */
		$glob = glob(DB . '/*.db');
		$glob = ($glob !== false && $glob !== []) ? $glob : [];
		foreach ($glob as $fil) {
			$databaser .= t(8, "<em>{$this->eka($fil)}</em> ({$this->eka(intval(filesize($fil)) / 1024 . ' kB')})<br>");
		}

		echo <<< EOT
			<div id="flikar-preferenser">
{$this->övre_grid()}
				<div class="generell-nedre-grid">
					<div class="generell-nedre">
						<div style="color: #444; font-size: 90%;">
							<p>
								Tips version {$this->eka(VERSION)} {$this->eka(VERSIONSDATUM)}<br>
								{$this->eka(trim((string) shell_exec('httpd -version | cut -d " " -f3')))}<br>
								PHP {$this->eka(phpversion() . ' | ' . $this->php)}<br>
								SQLite3 {$this->eka(SQLite3::version()['versionString'])}<br>
								SAPI {$this->eka(PHP_SAPI . ' | ' . $this->fcgi)}</p>
						</div>
						<hr class="kort">
						<div>
							<p>Databas: {$this->eka($this->db->integritetskontroll())}</p>
							<p>
$databaser							</p>
						</div>
					</div> <!-- generell-nedre -->
				</div> <!-- generell-nedre-grid -->
			</div> <!-- flikar-preferenser -->

EOT;
	}

	/**
	 * Övre grid i flik med preferenser.
	 */
	private function övre_grid(): string {
		/**
		 * Parallella trådar.
		 */
		$trådar = array_fill_keys(TRÅDMÄNGD, '');
		$trådar[$this->trådar] = ' checked="checked"';

		/**
		 * Eka ut.
		 */
		return <<< EOT
				<div class="generell-övre-grid">
					<div class="generell-övre">
						<h1>Preferenser</h1>
						<div id="preferenser-tradar">
							<p>Parallella trådar:
							<label>1<input type="radio" value="1" name="trådar"{$trådar[1]}></label>
							<label>3<input type="radio" value="3" name="trådar"{$trådar[3]}></label>
							<label>9<input type="radio" value="9" name="trådar"{$trådar[9]}></label>
							<label>27<input type="radio" value="27" name="trådar"{$trådar[27]}></label>
							<label>81<input type="radio" value="81" name="trådar"{$trådar[81]}></label></p>
						</div>
						<hr class="kort">
						<div>
							<table>
								<tr>
									<th class="ramfri">PHP:</th>
									<td class="ramfri"><input type="text" size="50" value="{$this->php}" id="preferenser-php"></td>
								</tr>
								<tr>
									<th class="ramfri">FCGI:</th>
									<td class="ramfri"><input type="text" size="50" value="{$this->fcgi}" id="preferenser-fcgi"></td>
								</tr>
								<tr>
									<td colspan="2" class="ramfri">
										<p><button id="preferenser-spara-php" type="submit">Spara</button></p>
									</td>
								</tr>
							</table>
						</div>
						<hr class="kort">
						<div>
							<p>API: <input type="text" size="45" value="{$this->api}" id="preferenser-api">
								<button id="preferenser-spara-api" type="submit">Spara</button></p>
						</div>
					</div> <!-- generell-övre -->
				</div> <!-- generell-övre-grid -->
EOT;
	}
}
