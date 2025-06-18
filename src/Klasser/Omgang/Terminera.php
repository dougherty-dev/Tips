<?php

/**
 * Klass Terminera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;

/**
 * Klass Terminera.
 * Uppmärkning för att avsluta renderingen.
 */
final class Terminera {
	/**
	 * Initiera.
	 */
	public function __construct(private Tips $tips) {
		$this->terminera_uppmärkning();
	}

	/**
	 * Terminera uppmärkning.
	 * Lägg till JS och CSS för moduler.
	 */
	public function terminera_uppmärkning(): void {
		$jscss = '';
		$version = VERSIONSDATUM;
		foreach (['js', 'css'] as $typ) {
			$glob = (array) glob(MODULER . "/$typ/*.$typ");
			foreach ($glob as $filnamn) {
				$modul = basename((string) $filnamn, ".$typ");
				if (in_array($modul, $this->tips->moduler->aktiva_moduler, true)) {
					$jscss .= match ($typ) {
						'js' => t(2, "<script type=\"module\" src=\"/moduler/js/$modul.js?$version\"></script>"),
						'css' => t(2, "<link rel=\"stylesheet\" type=\"text/css\" href=\"/moduler/css/$modul.css?$version\">")
					};
				}
			}
		}

		echo <<< EOT
		</div> <!-- flikar -->
	</main> <!-- omgångsdata -->
	<footer>
		<script type="module" src="/js/funktioner.js?$version"></script>
		<script type="module" src="/js/gemensamt.js?$version"></script>
$jscss	</footer>
</body>
</html>

EOT;

		/**
		 * Rödmarkera flikhuvud om loggtext innehåller felmeddelanden.
		 */
		if (defined('FEL')) {
			echo "<script>$(\"#li-logg a\").css(\"color\", \"red\");</script>";
		}
	}
}
