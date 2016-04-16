<?php

namespace Meridius\TesterExtras;

use Nette\Caching\Storages\FileStorage;
use Nette\Loaders\RobotLoader;
use Tester\Environment;
use Tester\Helpers;
use Tracy\Debugger;

class Bootstrap {

	/**
	 * Setup testing environment
	 * @param string $rootDir absolute path to the root of the project
	 * @global string $_GLOBALS['TEMP_DIR'] TEMP_DIR is defined here
	 */
	public static function setup($rootDir) {
		// configure environment
		umask(0);
		Environment::setup();
		class_alias('Tester\Assert', 'Assert');
		date_default_timezone_set('Europe/Prague');

		// create temporary directory
		$uniqueName = isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid();
		define('TEMP_DIR', $rootDir . '/tmp/' . $uniqueName);
		Helpers::purge(TEMP_DIR);
		@chmod(TEMP_DIR, 0777); // intentional silencer
		Debugger::$logDirectory = TEMP_DIR;
	}

	/**
	 *
	 * @param string|string[] $dirs absolute paths to directories for robot loading
	 * @return RobotLoader
	 */
	public static function createRobotLoader($dirs = []) {
		if (!is_array($dirs)) {
			$dirs = func_get_args();
		}

		$loader = new RobotLoader();
		$loader->setCacheStorage(new FileStorage(TEMP_DIR));
		$loader->autoRebuild = true;

		foreach ($dirs as $dir) {
			$loader->addDirectory($dir);
		}

		return $loader->register();
	}

}
