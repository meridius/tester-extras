<?php

namespace Meridius\TesterExtras;

use LogicException;
use Nette\Database\Connection;
use Nette\DI\Container;

trait NetteDatabaseSetupTrait {

	use CompiledContainerTrait {
		createContainer as parentCreateContainer;
	}

	/**
	 * @var string|NULL
	 */
	protected $databaseName;

	/**
	 *
	 * @param string[] $configs
	 * @return Container
	 * @throws LogicException
	 */
	protected function createContainer(array $configs = []) {
		$container = $this->parentCreateContainer($configs);

		/* @var Connection $db */
		$db = $container->getByType(Connection::class);
		if (!$db instanceof Connection) {
			throw new LogicException('Connection service should be instance of ' . Connection::class);
		}

		$db->onConnect[] = function (Connection $db) use ($container) {
			if ($this->databaseName !== null) {
				return;
			}
			$this->setupDatabase($db);
		};

		$db->reconnect(); // because we're not connected to any DB at first, just to server

		return $container;
	}

	/**
	 *
	 * @param Connection $db
	 */
	private function setupDatabase(Connection $db) {
		$this->databaseName = 'db_tests_' . getmypid();

		$this->dropDatabase($db);
		$this->createDatabase($db);
		$this->setupDatabaseContent($db);

		register_shutdown_function(function () use ($db) {
			$this->dropDatabase($db);
		});
	}

	/**
	 * Method for creating the testing database
	 * @param Connection $db
	 */
	protected function createDatabase(Connection $db) {
		$db->query("CREATE DATABASE {$this->databaseName}");
		$db->query("USE {$this->databaseName}");
	}

	/**
	 * Method for dropping the testing database
	 * @param Connection $db
	 */
	protected function dropDatabase(Connection $db) {
		$db->query("DROP DATABASE IF EXISTS {$this->databaseName}");
	}

}
