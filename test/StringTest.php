<?php
namespace zpt\util\test;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/test-common.php';
require_once __DIR__ . '/../zpt/util/prim-wrap.php';

/**
 * This class tests the primitive wrapper functions.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class PrimitiveWrapperTest extends TestCase {

  public function testString() {
    $this->assertTrue( String('hello')->endsWith('lo') );
  }
}
