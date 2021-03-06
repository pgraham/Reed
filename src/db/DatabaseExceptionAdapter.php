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
namespace zpt\util\db;

use \PDOException;

/**
 * Interface for classes which parse database driver specific error messages in
 * order to produce DatabaseException instances.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
interface DatabaseExceptionAdapter
{

	/**
	 * Transform the given PDOException into a DatabaseException which can be
	 * rethrown. DatabaseException provides an interface for programatically
	 * determining the cause of the PDOException.
	 *
	 * @param PDOException $e
	 * @return DatabaseException
	 */
	public function adapt(PDOException $e);
}
