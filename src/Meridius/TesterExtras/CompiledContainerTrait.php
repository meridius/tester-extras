<?php

namespace Meridius\TesterExtras;

use Nette\Configurator;
use Nette\DI\Container;
use Nette\Http\Session;
use const TEMP_DIR;

trait CompiledContainerTrait {

	/** @var Container */
	private $container;

	/**
	 * @param string[] $configs
	 * @return Container
	 */
	protected function getContainer(array $configs = []) {
		if (!$this->isContainerCreated()) {
			$this->container = $this->createContainer($configs);
		}

		return $this->container;
	}

	/**
	 * @return bool
	 */
	protected function isContainerCreated() {
		return $this->container !== NULL;
	}

	/**
	 * @param string[] $configs
	 * @return Container
	 */
	protected function createContainer(array $configs = []) {
		$configurator = $this->createConfigurator();

		foreach ($configs as $file) {
			$configurator->addConfig($file);
		}

		return $configurator->createContainer();
	}

	/**
	 * @return Configurator
	 */
	protected function createConfigurator() {
		// /vendor/meridius/tester-extras/src/Meridius/TesterExtras/
		$rootDir = __DIR__ . '/../../../../../..';
		$config = new Configurator();
		$config->addParameters([
			'rootDir' => $rootDir,
			'appDir' => $rootDir . '/app',
			'wwwDir' => $rootDir . '/www',
		]);

		// shared compiled container for faster tests
		$config->setTempDirectory(dirname(TEMP_DIR));

		return $config;
	}

	/**
	 * @todo there seems to be no Nette\DI\Container::initialize
	 */
	protected function refreshContainer() {
		$container = $this->getContainer();

		/** @var Session $session */
		$session = $container->getByType('Nette\Http\Session');
		if ($session && $session->isStarted()) {
			$session->close();
		}

		$this->container = new $container();
		$this->container->initialize();
	}

	/**
	 * @return bool
	 */
	protected function tearDownContainer() {
		if ($this->container) {
			/** @var Session $session */
			$session = $this->getContainer()->getByType('Nette\Http\Session');
			if ($session->isStarted()) {
				$session->destroy();
			}

			$this->container = NULL;

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * @param string $type
	 * @return object
	 */
	protected function getService($type) {
		$container = $this->getContainer();
		$object = $container->getByType($type, FALSE);
		return $object ?: $container->createInstance($type);
	}

}
