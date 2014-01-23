<?php
/**
 * =============================================================================
<<<<<<< Updated upstream
 * Copyright (c) 2014, Philip Graham
=======
 * Copyright (c) 2013, Philip Graham
>>>>>>> Stashed changes
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under the
 * 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 * =============================================================================
 *
 * This file sets up the environment for running tests.
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

// Find composer vendor directory.
$dir = dirname(__DIR__);
while (!file_exists($dir . DIRECTORY_SEPARATOR . '/vendor')) {
	$dir = dirname($dir);
}
$composerAutoloaderPath = implode(DIRECTORY_SEPARATOR, [
	$dir,
	'vendor',
	'autoload.php'
]);
$loader = require $composerAutoloaderPath;
