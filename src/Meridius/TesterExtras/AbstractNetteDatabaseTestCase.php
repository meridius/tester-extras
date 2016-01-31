<?php

namespace Meridius\TesterExtras;

use Nette\Database\Connection;
use Tester\TestCase;

/**
 * Each test method will get its own database
 */
abstract class AbstractNetteDatabaseTestCase extends TestCase {

	use NetteDatabaseSetupTrait;

	/**
	 * This will jump-start the whole process
	 * @param string[] $configs
	 */
	public function __construct(array $configs = []) {
		$this->getContainer($configs);
	}

	/**
	 * This is for populating the created database with testing data
	 * @param Connection $db
	 */
	abstract protected function setupDatabaseContent(Connection $db);

}
