<?php

/**
 * @testCase
 */

namespace MeridiusTests;

use Meridius\TesterExtras\AbstractNetteDatabaseTestCase;
use Meridius\TesterExtras\Bootstrap;
use MeridiusTests\NetteDatabaseTest;
use MeridiusTests\TesterExtras\UserManager;
use Nette\Database\Connection;
use Nette\Database\Helpers;
use Nette\Database\IRow;
use Nette\Utils\Finder;
use Tester\Assert;

require_once __DIR__ . '/../vendor/autoload.php';

Bootstrap::setup(__DIR__);
Bootstrap::createRobotLoader(__DIR__ . '/files');

class NetteDatabaseTest extends AbstractNetteDatabaseTestCase {

	/** @var UserManager */
	private $userManager;

	public function __construct() {
		parent::__construct([__DIR__ . '/tests.nette.database.neon']);
		$this->userManager = $this->getService(UserManager::class);
	}

	protected function setupDatabaseContent(Connection $db) {
		$files = Finder::findFiles('*.sql')->in(__DIR__ );
		foreach ($files as $file) {
			Helpers::loadFromFile($db, $file);
		}
	}

	public function testExistingId() {
		$row = $this->userManager->getById(3);
		Assert::type(IRow::class, $row);
	}

	public function testNonExistingId() {
		$row = $this->userManager->getById(0);
		Assert::false($row);
	}

}

(new NetteDatabaseTest())->run();
