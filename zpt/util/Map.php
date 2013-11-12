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

/**
 * An assortment of functions for manipulating associative arrays.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Map {

	/**
	 * Create an array which glues the key => value pairs in the given associative
	 * array with the specified glue.
	 *
	 * Example:
	 *
	 *     Map::zip('=', [ 'host' => 'localhost', 'schema' => 'my_schema' ]);
	 *     // [ 'host=localhost', 'schema=my_schema' ]
	 */
	public static function zip($glue, $map) {
		$glued = [];
		foreach ($map as $k => $v) {
			$glued[] = "$k$glue$v";
		}
		return $glued;
	}
}
