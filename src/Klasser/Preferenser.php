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
 */
final class Preferenser {
	use Varden;
	use Eka;

	public bool $avlusa;

	public function __construct(public Databas $db) {
		$this->hämta_värden($this->db);
		$this->trådar = in_array($this->trådar, TRÅDMÄNGD, true) ? $this->trådar : 9;
		$this->avlusa = (bool) (new DBPreferenser($this->db))->hämta_preferens('preferenser.avlusa');
		$this->visa_preferenser();
	}

	/**
	 * Visa preferenser.
	 */
	private function visa_preferenser(): void {
		$trådar = array_fill_keys(TRÅDMÄNGD, '');
		$avlusa_vald = ['', ''];
		$trådar[$this->trådar] = $avlusa_vald[(int) $this->avlusa] = ' checked="checked"';

		$avlusningssträng = '';
		if ($this->avlusa) {
			$jsondata = ''; // is_file(JSONFIL) ? dumpa_objekt((object) json_decode((string) file_get_contents(JSONFIL))) : '';
			$avlusningssträng = <<< EOT
					<br>
					<div>
						<textarea rows="40" cols="100">
$jsondata						</textarea>
					</div>
					<div>
{$this->avlusa()}				</div>

EOT;
		}

		$databaser = '';
		$glob = glob(DB . '/*.db');
		$glob = ($glob !== false && $glob !== []) ? $glob : [];
		foreach ($glob as $fil) {
			$databaser .= t(8, "<em>{$this->eka($fil)}</em> ({$this->eka(intval(filesize($fil)) / 1024 . ' kB')})<br>");
		}

		echo <<< EOT
			<div id="flikar-preferenser">
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
						<div id="preferenser-avlusa">
							<p>Avlusa: <label>Av<input type="radio" value="0" name="avlusa"{$avlusa_vald[0]}></label>
							<label>På<input type="radio" value="1" name="avlusa"{$avlusa_vald[1]}></label></p>
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
				<div class="generell-nedre-grid">
					<div class="generell-nedre">
						<div style="color: #444; font-size: 90%;">
							<p>
								Tips version {$this->eka(VERSION)} {$this->eka(VERSIONSDATUM)}<br>
								{$this->eka(trim((string) shell_exec('httpd -version | cut -d " " -f3')))}<br>
								PHP {$this->eka(phpversion() . ' | ' . $this->php)}<br>
								SQLite3 {$this->eka(SQLite3::version()['versionString'])}<br>
								SAPI {$this->eka(PHP_SAPI . ' | ' . $this->fcgi)}</p>
							<p>Senaste dragning vid Svenska spel: {$this->eka(is_file(JSONFIL) ? date("Y-m-d H:i:s", (int) filemtime(JSONFIL)) : '')}</p>
						</div>
						<hr class="kort">
						<div>
							<p>Databas: {$this->eka($this->db->integritetskontroll())}</p>
							<p>
$databaser							</p>
						</div>
$avlusningssträng					</div> <!-- generell-nedre -->
				</div> <!-- generell-nedre-grid -->
			</div> <!-- flikar-preferenser -->

EOT;
	}

	/**
	 * Avlusa.
	 */
	private function avlusa(): string {
		if ($_REQUEST !== []) {
			ob_start();
			var_dump($_REQUEST);
			return (string) ob_get_clean();
		}
		return '<p>Avlusningsläge</p>';
	}
}
