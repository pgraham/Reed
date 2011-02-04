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
 * @package reed/test/util
 */
namespace reed\test\util;

use \reed\util\ReflectionHelper;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/../test-common.php';

/**
 * This class tests the Reed\Util\ReflectionHelper class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package reed/test/util
 */
class ReflectionHelperTest extends TestCase {

  /**
   * Tests that a doc comment that contains a single annotation with no
   * parameters is parsed correctly.
   */
  public function testOneAnnotationNoParams() {
    $comment = <<<'EOT'
/**
 * This is a comment that contains a single annotation with no parameters.
 *
 * @Entity
 */
EOT;
    
    $annotations = ReflectionHelper::getAnnotations($comment);
    $msg = print_r($annotations, true);

    $this->assertInternalType('array', $annotations, $msg);
    $this->assertArrayHasKey('entity', $annotations, $msg);
    $this->assertInternalType('array', $annotations['entity'], $msg);
  }

  /**
   * Tests that a doc comment that contains a single annotation with one
   * parameter is parsed correctly.
   */
  public function testOneAnnotationOneParam() {
    $comment = <<<'EOT'
/**
 * This is a comment that contains a single annotation with one parameter.
 *
 * @Entity(name = table)
 */
EOT;

    $annotations = ReflectionHelper::getAnnotations($comment);
    $msg = print_r($annotations, true);

    $this->assertInternalType('array', $annotations, $msg);
    $this->assertArrayHasKey('entity', $annotations, $msg);

    $annoVal = $annotations['entity'];
    $this->assertInternalType('array', $annoVal, $msg);
    $this->assertArrayHasKey('name', $annoVal, $msg);
    $this->assertEquals('table', $annoVal['name'], $msg);
  }

  /**
   * Tests that a doc comment that contains a single annotation with two
   * parameters is parsed correctly.
   */
  public function testOneAnnotationTwoParams() {
    $comment = <<<'EOT'
/**
 * This is a comment that contains a single annotation with two parameters.
 *
 * @Entity(name = table, desc = Entity that represents a table)
 */
EOT;

    $annotations = ReflectionHelper::getAnnotations($comment);
    $msg = print_r($annotations, true);

    $this->assertInternalType('array', $annotations, $msg);
    $this->assertArrayHasKey('entity', $annotations, $msg);

    $annoVal = $annotations['entity'];
    $this->assertInternalType('array', $annoVal, $msg);
    $this->assertArrayHasKey('name', $annoVal, $msg);
    $this->assertEquals('table', $annoVal['name'], $msg);
    $this->assertArrayHasKey('desc', $annoVal, $msg);
    $this->assertEquals('Entity that represents a table', $annoVal['desc'],
      $msg);
  }

  /**
   * Tests that a doc comment that contains a single annotation with multiple
   * parameters is parsed correctly.
   */
  public function testOneAnnotationMultipleParams() {
    $comment = <<<'EOT'
/**
 * This is a comment that contains a single annotations with multiple parameters
 *
 * @Entity(name = table, desc = Entity that represents a table, parent = database)
 */
EOT;

    $annotations = ReflectionHelper::getAnnotations($comment);
    $msg = print_r($annotations, true);

    $this->assertInternalType('array', $annotations, $msg);
    $this->assertArrayHasKey('entity', $annotations, $msg);

    $annoVal = $annotations['entity'];
    $this->assertInternalType('array', $annoVal, $msg);
    $this->assertArrayHasKey('name', $annoVal, $msg);
    $this->assertEquals('table', $annoVal['name'], $msg);
    $this->assertArrayHasKey('desc', $annoVal, $msg);
    $this->assertEquals('Entity that represents a table', $annoVal['desc'],
      $msg);
    $this->assertArrayHasKey('parent', $annoVal, $msg);
    $this->assertEquals('database', $annoVal['parent'], $msg);
  }

  /**
   * Tests that a doc comment that contains a multiple annotations with no
   * parameters is parsed correctly.
   */
  public function testMultipleAnnotationsNoParams() {
    $comment = <<<'EOT'
/**
 * This is a comment that contains multiple annotations with no parameters.
 *
 * @Hotdog
 * @Hamburger
 * @KraftDinner
 */
EOT;

    $annotations = ReflectionHelper::getAnnotations($comment);
    $msg = print_r($annotations, true);

    $this->assertInternalType('array', $annotations, $msg);
    $this->assertArrayHasKey('hotdog', $annotations, $msg);
    $this->assertArrayHasKey('hamburger', $annotations, $msg);
    $this->assertArrayHasKey('kraftdinner', $annotations, $msg);
  }

  /**
   * Tests that a doc comment that contains multiple annotations with multiple
   * parameters is parsed correctly.
   */
  public function testMultipleAnnotationsMultipleParams() {
    $comment = <<<'EOT'
/**
 * This is a comment that contains multiple annotations with multiple parameters
 *
 * @Hotdog(brand = Maple Leaf, bun = true, cooking_method = BBQ)
 * @Hamburger(brand = Home Made, bun = true, cooking_method = BBQ)
 * @KraftDinner(brand = Kraft, bun = false, cooking_method = stove)
 */
EOT;

    $annotations = ReflectionHelper::getAnnotations($comment);
    $msg = print_r($annotations, true);

    $this->assertInternalType('array', $annotations, $msg);
    $this->assertArrayHasKey('hotdog', $annotations, $msg);
    $this->assertArrayHasKey('hamburger', $annotations, $msg);
    $this->assertArrayHasKey('kraftdinner', $annotations, $msg);

    $annoVal = $annotations['hotdog'];
    $this->assertInternalType('array', $annoVal, $msg);
    $this->assertArrayHasKey('brand', $annoVal, $msg);
    $this->assertArrayHasKey('bun', $annoVal, $msg);
    $this->assertArrayHasKey('cooking_method', $annoVal, $msg);
    $this->assertEquals('Maple Leaf', $annoVal['brand'], $msg);
    $this->assertTrue($annoVal['bun'], $msg);
    $this->assertEquals('BBQ', $annoVal['cooking_method'], $msg);

    $annoVal = $annotations['hamburger'];
    $this->assertInternalType('array', $annoVal, $msg);
    $this->assertArrayHasKey('brand', $annoVal, $msg);
    $this->assertArrayHasKey('bun', $annoVal, $msg);
    $this->assertArrayHasKey('cooking_method', $annoVal, $msg);
    $this->assertEquals('Home Made', $annoVal['brand'], $msg);
    $this->assertTrue($annoVal['bun'], $msg);
    $this->assertEquals('BBQ', $annoVal['cooking_method'], $msg);

    $annoVal = $annotations['kraftdinner'];
    $this->assertInternalType('array', $annoVal, $msg);
    $this->assertArrayHasKey('brand', $annoVal, $msg);
    $this->assertArrayHasKey('bun', $annoVal, $msg);
    $this->assertArrayHasKey('cooking_method', $annoVal, $msg);
    $this->assertEquals('Kraft', $annoVal['brand'], $msg);
    $this->assertFalse($annoVal['bun'], $msg);
    $this->assertEquals('stove', $annoVal['cooking_method'], $msg);
  }

  /**
   * Tests that annotation values that are enclosed in braces are parsed as an
   * array of values.
   */
  public function testArrayValue() {
    $comment = <<<'EOT'
/**
 * This is a comment to test that array value are parsed properly.
 *
 * @HasArray(array = { one, two, three })
 * @HasTwoArrays(array1 = { four, five, six }, array2 = { seven, eight, nine })
 * @HasThreeArrays(array1 = { ten, eleven, twelve }, array2 = { thirteen, fourteen, fifteen }, array3 = { sixteen, seventeen, eighteen })
 */
EOT;

    $annotations = ReflectionHelper::getAnnotations($comment);
    $msg = print_r($annotations, true);

    $this->assertInternalType('array', $annotations, $msg);
    $this->assertArrayHasKey('hasarray', $annotations, $msg);
    $this->assertArrayHasKey('hastwoarrays', $annotations, $msg);
    $this->assertArrayHasKey('hasthreearrays', $annotations, $msg);

    $hasArray = $annotations['hasarray'];
    $this->assertInternalType('array', $hasArray, $msg);
    $this->assertArrayHasKey('array', $hasArray, $msg);
    $this->assertInternalType('array', $hasArray['array'], $msg);
    $this->assertContains('one', $hasArray['array'], $msg);
    $this->assertContains('two', $hasArray['array'], $msg);
    $this->assertContains('three', $hasArray['array'], $msg);

    $hasTwoArrays = $annotations['hastwoarrays'];
    $this->assertInternalType('array', $hasTwoArrays, $msg);
    $this->assertArrayHasKey('array1', $hasTwoArrays, $msg);
    $this->assertArrayHasKey('array2', $hasTwoArrays, $msg);
    $this->assertInternalType('array', $hasTwoArrays['array1'], $msg);
    $this->assertInternalType('array', $hasTwoArrays['array2'], $msg);
    $this->assertContains('four', $hasTwoArrays['array1'], $msg);
    $this->assertContains('five', $hasTwoArrays['array1'], $msg);
    $this->assertContains('six', $hasTwoArrays['array1'], $msg);
    $this->assertContains('seven', $hasTwoArrays['array2'], $msg);
    $this->assertContains('eight', $hasTwoArrays['array2'], $msg);
    $this->assertContains('nine', $hasTwoArrays['array2'], $msg);

    $hasThreeArrays = $annotations['hasthreearrays'];
    $this->assertInternalType('array', $hasThreeArrays, $msg);
    $this->assertArrayHasKey('array1', $hasThreeArrays, $msg);
    $this->assertArrayHasKey('array2', $hasThreeArrays, $msg);
    $this->assertArrayHasKey('array3', $hasThreeArrays, $msg);
    $this->assertInternalType('array', $hasThreeArrays['array1'], $msg);
    $this->assertInternalType('array', $hasThreeArrays['array2'], $msg);
    $this->assertInternalType('array', $hasThreeArrays['array3'], $msg);
    $this->assertContains('ten', $hasThreeArrays['array1'], $msg);
    $this->assertContains('eleven', $hasThreeArrays['array1'], $msg);
    $this->assertContains('twelve', $hasThreeArrays['array1'], $msg);
    $this->assertContains('thirteen', $hasThreeArrays['array2'], $msg);
    $this->assertContains('fourteen', $hasThreeArrays['array2'], $msg);
    $this->assertContains('fifteen', $hasThreeArrays['array2'], $msg);
    $this->assertContains('sixteen', $hasThreeArrays['array3'], $msg);
    $this->assertContains('seventeen', $hasThreeArrays['array3'], $msg);
    $this->assertContains('eighteen', $hasThreeArrays['array3'], $msg);

  }

  /**
   * Test that values defined inside of quotes are parsed properly.
   */
  public function testQuotedValue() {
    $expected = "This is a description, contained in quotes, that has commas";
    $comment = <<<EOT
/**
 * This is a comment that contains a quoted value.
 *
 * @Description(value = "$expected")
 */
EOT;

    $annotations = ReflectionHelper::getAnnotations($comment);
    $msg = print_r($annotations, true);

    $this->assertInternalType('array', $annotations, $msg);
    $this->assertArrayHasKey('description', $annotations, $msg);

    $description = $annotations['description'];
    $this->assertInternalType('array', $description, $msg);
    $this->assertArrayHasKey('value', $description, $msg);

    $this->assertEquals($expected, $description['value'], $msg);
  }
}
