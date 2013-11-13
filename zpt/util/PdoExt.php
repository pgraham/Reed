<?php
/**
 * =============================================================================
 * Copyright (c) 2012, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 * =============================================================================
 */
namespace zpt\util;

use \zpt\util\db\MysqlAdapter;
use \zpt\util\db\PgsqlAdapter;
use \zpt\util\db\SqlAdminAdapter;
use \Exception;
use \PDO;

/**
 * Pdo Extension that provides an additional layer of abstraction for
 * normalizing database administration level commands.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class PdoExt extends PDO implements SqlAdminAdapter {

	private static $DEFAULT_OPTS = [
		'host'          => 'localhost',
		'database'      => '',
		'dsnOptions'    => [],
		'pdoOptions'    => [],
		'pdoAttributes' => []
	];

	private $adapter;

	private $driver;
	private $host;
	private $username;
	private $password;

	private $dsnOptions;
	private $options;
	private $attributes;

	public function __construct($options) {
		$opts = $this->applyDefaultOptions($options);

		$this->driver = $opts['driver'];
		$this->host = $opts['host'];
		$this->username = $opts['username'];
		$this->password = $opts['password'];
		$this->database = $this->extractOpt($opts, 'database');
		$this->dsnOptions = $this->extractOpt($opts, 'dsnOptions', []);
		$this->options = $this->extractOpt($opts, 'pdoOptions', []);
		$this->attributes = $this->extractOpt($opts, 'pdoAttributes', []);

		switch ($this->driver) {
			case 'mysql':
			$this->adapter = new MysqlAdapter($this, $options);
			break;

			case 'pgsql':
			$this->adapter = new PgsqlAdapter($this, $options);
			break;

			default:
			throw new Exception("Unsupported driver $dbdriver");
		}

		$dsn = Db::buildDsn(
			$this->driver,
			$this->host,
			$this->database,
			$this->dsnOptions
		);

		parent::__construct(
			$dsn,
			$this->username,
			$this->password,
			$this->options
		);

		foreach ($this->attributes as $key => $value) {
			$this->setAttribute($key, $value);
		}
	}

	public function createDatabase($name, $charSet) {
		$this->adapter->createDatabase($name, $charSet);
	}

	public function createUser($username, $passwd, $host = null) {
		$this->adapter->createUser($username, $passwd, $host);
	}

	public function dropDatabase($name) {
		$this->adapter->dropDatabase($name);
	}

	public function dropUser($username, $host = null) {
		$this->adapter->dropUser($username, $host);
	}

	public function grantUserPermissions(
		$db,
		$username,
		$permissions,
		$host = null
	) {
		$this->adapter->grantUserPermissions($db, $username, $permissions, $host);
	}

	/**
	 * Create a new connection with the same parameters unless otherwise
	 * specified.
	 */
	public function newConnection($options) {
		$opts = $this->applyCurrentOptions($options);
		return new PdoExt($opts);
	}

	private function applyCurrentOptions($options) {
		return array_merge([], [
			'driver'        => $this->driver,
			'host'          => $this->host,
			'username'      => $this->username,
			'password'      => $this->password,
			'database'      => $this->database,
			'dsnOptions'    => $this->dsnOptions,
			'pdoOptions'    => $this->options,
			'pdoAttributes' => $this->attributes
		], $options);
	}

	private function applyDefaultOptions($options) {
		$merged = array_merge([], self::$DEFAULT_OPTS, $options);

		return $merged;
	}

	private function extractOpt(array $opts, $optName, $default = null) {
		return isset($opts[$optName]) ? $opts[$optName] : $default;
	}
}
