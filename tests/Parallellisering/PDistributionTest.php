<?php

/**
 * Klass PDistributionTest.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Tests\Parallellisering;

use PHPUnit\Framework\TestCase;
use Tips\Klasser\Preludium;
use Tips\Parallellisering\PDistribution;

/**
 * Klass PDistributionTest.
 */
class PDistributionTest extends TestCase
{
	/**
	 * Testa PDistribution med metoder.
	 */
	public function testPDistribution(): void
	{
		new Preludium();

		$_REQUEST['i'] = '1';
		$_REQUEST['j'] = '0';
		$_REQUEST['k'] = '0';
		$_REQUEST['l'] = '0';
		unlink(GRAF . DISTRIBUTION . "/stryktipset-t1-o4905-s1.png");
		$this->assertInstanceOf("\Tips\Parallellisering\PDistribution", new PDistribution());
		unset($_REQUEST['i'], $_REQUEST['j'], $_REQUEST['k'], $_REQUEST['l']);
	}
}
