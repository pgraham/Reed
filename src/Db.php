<?php
/**
 * =============================================================================
 * Copyright (c) 2013, Philip Graham
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

use \PDO;

/**
 * An assortment of database related functionality.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Db {

	const PERMISSION_SELECT = 0b0001;
	const PERMISSION_INSERT = 0b0010;
	const PERMISSION_UPDATE = 0b0100;
	const PERMISSION_DELETE = 0b1000;

	const CRUD_PERMISSIONS = 0b1111;

	const UTF8 = 'utf8';

	/**
	 * Create a DSN for connecting to the specified database engine. Currently
	 * supports MySQL and PostgreSQL but may work for other databases.
	 *
	 * @param string $driver
	 * @param string $host
	 * @param string $schema
	 * @param array $opts Additional options
	 */
	public static function buildDsn($driver, $host = null, $schema = null,
		$opts = []
	) {
		$merged = [];
		if ($host) {
			$merged['host'] = $host;
		}
		if ($schema) {
			$merged['dbname'] = $schema;
		}
		$merged = array_merge($merged, $opts);
		return "$driver:" . implode(';', Map::zip('=', $merged));
	}

	public static function pdoConnect($driver, $user, $pw, $host, $schema,
		$dsnOpts = [], $pdoOpts = [], $pdoAttrs = []
	) {
		$dsn = self::buildDsn($driver, $host, $schema, $dsnOpts);
		$pdo = new PDO($dsn, $user, $pass, $pdoOpts);

		foreach ($pdoAttrs as $key => $value) {
			$pdo->setAttribute($key, $value);
		}
	}
}
