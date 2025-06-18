<?php

/**
 * Klass TestsFinishedSubscriber.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Extension;

use PHPUnit\Event\Application\Finished;
use PHPUnit\Event\Application\FinishedSubscriber;

/**
 * Klass TestsFinishedSubscriber.
 * Implementera shutdown efter att alla tester är avslutade.
 * Tester sker mot en särskild testmapp med tillhörande testdatabas.
 * Denna mapp aktiveras respektive avaktiveras i början och slut av testning.
 * @SuppressWarnings("PHPMD.UnusedFormalParameter")
 */
class TestsFinishedSubscriber implements FinishedSubscriber {
	public function notify(Finished $event): void {
		echo "Avaktiverar och återställer testdatabas." . PHP_EOL;

		$dir = __DIR__ . '/../../src/_data';
		rename("$dir/test", "$dir/_test");
		copy("$dir/_test/db/original/tips.db", "$dir/_test/db/tips.db");
	}
}
