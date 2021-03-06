<?php
/**
 * Copyright (c) 2012, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zpt\util;

use \Exception;

/**
 * Exception that is thrown when aquiring a directory lock times out.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class DirectoryLockTimeoutException extends Exception { }
