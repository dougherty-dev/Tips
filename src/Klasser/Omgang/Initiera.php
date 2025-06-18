<?php

/**
 * Klass Initiera.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Klasser\Omgang;

use Tips\Klasser\Tips;
use Tips\Egenskaper\Huvud;

/**
 * Klass Initiera.
 */
final class Initiera {
	use Huvud;

	/**
	 * Initiera.
	 */
	public function __construct(private Tips $tips) {
		$this->initiera_uppmärkning();
	}

	/**
	 * Initiera uppmärkning.
	 */
	public function initiera_uppmärkning(): void {
		$modullänkar = '';
		foreach ($this->tips->moduler->aktiva_moduler as $modul) {
			$modullänkar .= t(5, "<li><a href=\"#modulflikar-$modul\"><span class=\"emoji\">⚙️</span> $modul</a></li>");
		}

		/**
		 * Eka ut flik i HTML.
		 */
		echo <<< EOT
{$this->huvud()}
<body>
	<header>
	</header>
	<main class="omgångsdata">
		<div id="flikar">
			<nav>
				<ul>
					<li><a href="#flikar-omg">Omgång</a></li>
					<li><a href="#flikar-genererat">Genererat</a></li>
					<li><a href="#flikar-spelat">Spelat</a></li>
$modullänkar					<li><a href="#flikar-modul">Modul</a></li>
					<li><a href="#flikar-scheman">Scheman</a></li>
					<li><a href="#flikar-investera">Investera</a></li>
					<li><a href="#flikar-preferenser">Preferens</a></li>
					<li id="li-logg"><a href="#flikar-logg">Logg</a></li>
				</ul>
			</nav>

EOT;
	}
}
