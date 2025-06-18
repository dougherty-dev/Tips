<?php

/**
 * Klass Parametrar.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Moduler\FANN;

/**
 * Klass Parametrar.
 */
class Parametrar extends Data {
	/**
	 * Parametrar för neuralt nätverk.
	 * Gränser för 1X2, feltolerans samt data för algoritm och stegfunktion.
	 */
	protected function fannparametrar(): string {
		return <<< EOT
						<table>
							<tr>
								<td>1</td>
								<td>&lt; {$this->limiter[0]}</td>
							</tr>
							<tr>
								<td>1X</td>
								<td>&lt; {$this->limiter[1]}</td>
							</tr>
							<tr>
								<td>X2</td>
								<td>&lt; {$this->limiter[2]}</td>
							</tr>
							<tr>
								<td>2</td>
								<td>≥ {$this->limiter[2]}</td>
							</tr>
						</table>
						<p>Feltolerans: <input class="nummer" type="number" min="0.0" max="0.20" step="0.01" autocomplete="off" id="fann_feltolerans" value="{$this->feltolerans}"></p>
						<p>Cascade 2, rprop, symmetrisk sigmoid.<br>Kontroll: senaste tio stryktips.</p>
EOT;
	}
}
