<?php
/**
 * =============================================================================
 * Copyright (c) 2010, Philip Graham
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
 * @package Reed_Test
 */

// Include PHPUnit
require_once 'PHPUnit/Framework.php';

// Reed is built off of oboe so include the oboe test-suite initializer in order
// to include oboe's classes and mock objects
// TODO - Create an OBOE_HOME environment variable rather than relying on a
// relative path
require_once dirname(__FILE__).'/../../../oboe/trunk/test/test-common.php';

// Require reed's autoloader and test autoloader
require_once dirname(dirname(__FILE__)).'/src/Reed/Autoloader.php';
require_once dirname(__FILE__).'/Reed/Autoloader.php';
