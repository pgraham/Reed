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
 * This class test's Reed's integration with the Oboe_Page class.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @package Reed_Test
 */
class Reed_OboePageIntegrationTest extends PHPUnit_Framework_TestCase {

    public function testOboePageInstance() {
        Reed_Page::init();

        $oboePage = Oboe_Page::getInstance();
        $this->assertTrue($oboePage instanceof Reed_Page);

        $reedPage = Reed_Page::getInstance();
        $this->assertEquals($oboePage, $reedPage,
            'Oboe_Page and Reed_Page singletons are not the same');
    }

    public function testElementAddToHead() {
        Reed_Page::init();
    }

    public function testElementAddToPage() {
        Reed_Page::init();
    }
}
