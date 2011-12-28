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
 */
namespace reed\test;

use \reed\Xml;
use \DOMXPath;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/test-common.php';

/**
 * This class tests the reed\Xml class' functions.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class XmlTest extends TestCase {

  public function testPatch() {
    $src = __DIR__ . '/xml-patch-src.xml';
    $patch = __DIR__ . '/xml-patch.xdiff';

    $patched = Xml::patch($src, $patch);

    $subXPath = new DOMXPath($patched);
    $nodes = $subXPath->query('/root/substitute');
    $this->assertEquals(1, $nodes->length);
    $this->assertEquals('new_value', $nodes->item(0)->textContent);

    $nodes = $subXPath->query('/root/flag');
    $this->assertEquals(0, $nodes->length);

    $nodes = $subXPath->query('/root/newflag');
    $this->assertEquals(1, $nodes->length);

    $nodes = $subXPath->query('/root/substitutecdata');
    $this->assertEquals(1, $nodes->length);
    $this->assertEquals(1, $nodes->item(0)->childNodes->length);
    $this->assertEquals(
      XML_CDATA_SECTION_NODE,
      $nodes->item(0)->childNodes->item(0)->nodeType
    );
  }

}
