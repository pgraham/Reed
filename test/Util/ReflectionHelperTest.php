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
 * @package ReedTest
 */
namespace ReedTest\Util;

use \Reed\Util\ReflectionHelper;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/../test-common.php';

/**
 * This class tests the Reed\Util\ReflectionHelper class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package ReedTest/Util
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
}
