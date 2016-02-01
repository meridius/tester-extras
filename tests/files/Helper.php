<?php

namespace MeridiusTests\TesterExtras;

class Helper extends \Nette\Object {

	/** @var string */
	private $myParam;

	/**
	 *
	 * @param string $myParam
	 */
	public function __construct($myParam) {
		$this->myParam = $myParam;
	}

	/**
	 *
	 * @return string
	 */
	public function getMyParam() {
		return $this->myParam;
	}

}
