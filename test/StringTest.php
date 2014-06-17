<?php
namespace zpt\util\test;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/test-common.php';

/**
 * This class tests the primitive wrapper functions.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class PrimitiveWrapperTest extends TestCase {

	public function testStringFromString() {
		$str1 = String('orig');
		$str2 = String($str1);

		$this->assertTrue($str1 === $str2);
	}

	public function testString() {
		$this->assertTrue( String('hello')->endsWith('lo') );
	}

	public function testTrim() {
		$str = String('  yo  ')->trim();
		$this->assertEquals('yo', $str);

		$str = String('!!yo!!')->trim('!');
		$this->assertEquals('yo', $str);

		$str = String('! yo !')->trim('!');
		$this->assertEquals(' yo ', $str);
	}

	public function testStripStart() {
		$str = String('HTTP/1.1')->stripStart('HTTP/');
		$this->assertEquals('1.1', $str);
	}
}
