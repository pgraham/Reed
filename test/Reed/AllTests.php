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
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @package Reed_Test
 */
require_once dirname(__FILE__).'/../test-common.php';

/**
 * This class builds the complete test suite for Reed.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @package Reed_Test
 */
class Reed_AllTests {

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('Reed Test Suite');

        $suite->addTestSuite('Reed_OboePageIntegrationTest');

        return $suite;
    }
}
