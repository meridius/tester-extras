<?php

namespace Meridius\TesterExtras;

use Tester\TestCase;

abstract class AbstractIntegrationTestCase extends TestCase {

	use CompiledContainerTrait;

	/**
	 * This will jump-start the whole process
	 * @param string[] $configs
	 */
	public function __construct(array $configs = []) {
		$this->getContainer($configs);
	}

}
