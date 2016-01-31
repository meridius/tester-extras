<?php

/**
 * @testCase
 */

namespace MeridiusTests;

use Meridius\TesterExtras\AbstractIntegrationTestCase;
use Meridius\TesterExtras\Bootstrap;
use MeridiusTests\TesterExtras\Helper;
use Tester\Assert;

require_once __DIR__ . '/../vendor/autoload.php';

Bootstrap::setup(__DIR__);
Bootstrap::createRobotLoader(__DIR__ . '/files');

class IntegrationTest extends AbstractIntegrationTestCase {

	/** @var Helper */
	private $helper;

	public function __construct() {
		parent::__construct([__DIR__ . '/tests.integration.neon']);
		$this->helper = $this->getService(Helper::class);
	}

	public function testMyParam() {
		$param = $this->helper->getMyParam();
		Assert::type('string', $param);
		Assert::same('mymy', $param); 
	}

}

(new IntegrationTest())->run();
