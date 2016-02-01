<?php

namespace MeridiusTests\TesterExtras;

use Nette\Database\Context;
use Nette\Object;

class UserManager extends Object {

	/** @var Context */
	private $db;

	public function __construct(Context $db) {
		$this->db = $db;
	}

	/**
	 *
	 * @param int $id
	 * @return \Nette\Database\IRow|false
	 */
	public function getById($id) {
		return $this->db->table('user')->where([
				'id' => $id,
			])->fetch();
	}

}
