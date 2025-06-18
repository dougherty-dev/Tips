<?php

/**
 * Klass Schema.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Scheman;

/**
 * Klass Schema.
 */
class Schema {
	/**
	 * Uppmärkning för scheman.
	 * Sammanfogar tabeller.
	 */
	protected function schema(string $scheman): string {
		return <<< EOT
			<div id="flikar-scheman">
				<div class="generell-övre-grid">
					<div class="generell-övre">
						<h1>Scheman</h1>
						<div id="schemamall">
$scheman							<br class="frigör">
							<p><button id="schema_nytt">Nytt schema</button></p>
						</div> <!-- schemamall -->
					</div> <!-- generell-övre -->
				</div> <!-- generell-övre-grid -->
			</div> <!-- flikar-scheman -->

EOT;
	}
}
