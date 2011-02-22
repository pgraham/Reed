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
 * @package reed/test/generator
 */
namespace reed\test\generator;

use \reed\generator\CodeTemplateLoader;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/../test-common.php';

/**
 * This class tests the CodeTemplateLoader class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package reed/test/generator
 */
class CodeTemplateLoaderTest extends TestCase {

  private $_loader;

  protected function setUp() {
    $this->_loader = new CodeTemplateLoader(__DIR__);
  }

  protected function tearDown() {
    $this->_loader = null;
  }

  public function testLoadBasic() {
    $template  = $this->_loader->load('simple', Array
      (
        '${sub1}' => 'val1',
        '${sub2}' => 'val2',
        '${sub3}' => 'val3'
      )
    );

    $expected = "This is a sample template with simple substitutions.\n\n"
      . "This line contains an inline substitution: val1\n"
      . "This line contains two substitutions: val2 val3\n"
      . "The following line contains a value on its own\n"
      . "val1\n";
      
    $this->assertEquals($expected, $template);
  }
}
