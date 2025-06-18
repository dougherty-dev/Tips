<?php

/**
 * Klass AfterWork.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Extension;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

/**
 * Klass AfterWork.
 * Registrera shutdown efter att alla tester är avslutade.
 * @SuppressWarnings("PHPMD.UnusedFormalParameter")
 */
class AfterWork implements Extension {
	public function bootstrap(
		Configuration $configuration,
		Facade $facade,
		ParameterCollection $parameters,
	): void {

		$facade->registerSubscribers(
			new TestsFinishedSubscriber(),
		);
	}
}
