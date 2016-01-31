<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Meridius\TesterExtras;

use Nette\Caching\Storages\FileStorage;
use Nette\Loaders\RobotLoader;
use Tester\Environment;
use Tester\Helpers;
use Tracy\Debugger;
use const TEMP_DIR;

/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class Bootstrap {

	/**
	 * Setup testing environment
	 * @global string $_GLOBALS['TEMP_DIR'] TEMP_DIR is defined here
	 * @param string $rootDir absolute path to the root of the project
	 */
	public static function setup($rootDir) {
		// configure environment
		umask(0);
		Environment::setup();
		class_alias('Tester\Assert', 'Assert');
		date_default_timezone_set('Europe/Prague');

		// create temporary directory
		define('TEMP_DIR', $rootDir . '/tmp/' . (isset($_SERVER['argv']) ? md5(serialize($_SERVER['argv'])) : getmypid()));
		Helpers::purge(TEMP_DIR);
		@chmod(TEMP_DIR, 0777); // intentional silencer
		Debugger::$logDirectory = TEMP_DIR;

//		$_SERVER = array_intersect_key($_SERVER, array_flip(array(
//			'PHP_SELF', 'SCRIPT_NAME', 'SERVER_ADDR', 'SERVER_SOFTWARE', 'HTTP_HOST', 'DOCUMENT_ROOT', 'OS', 'argc', 'argv'
//		)));
		$_SERVER['REQUEST_TIME'] = 1234567890;
		$_ENV = $_GET = $_POST = $_FILES = array();
	}

	/**
	 * 
	 * @param string[] $dirs absolute paths to directories for robot loading
	 * @return RobotLoader
	 */
	public static function createRobotLoader($dirs = array()) {
		if (!is_array($dirs)) {
			$dirs = func_get_args();
		}

		$loader = new RobotLoader;
		$loader->setCacheStorage(new FileStorage(TEMP_DIR));
		$loader->autoRebuild = TRUE;

		foreach ($dirs as $dir) {
			$loader->addDirectory($dir);
		}

		return $loader->register();
	}

}
