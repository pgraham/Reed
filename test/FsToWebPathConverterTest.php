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

use \reed\FsToWebPathConverter;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/test-common.php';

/**
 * This class tests the reed\FsToWebPathConverter class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class FsToWebPathConverterTest extends TestCase {

  /**
   * Test fsToWeb when constructed with web root = '/' and a doc root
   * without a trailing slash.
   */
  public function testSimpleWebDocNoSlash() {
    $fsToWeb = new FsToWebPathConverter(__DIR__);
    $converted = $fsToWeb->fsToWeb(__DIR__ . '/testfile');

    $this->assertEquals('/testfile', $converted);
  }

  /**
   * Test fsToWeb when constructed with web root = '/' and a doc root
   * with a trailing slash.
   */
  public function testSimpleWebDocSlash() {
    $fsToWeb = new FsToWebPathConverter(__DIR__ . '/');
    $converted = $fsToWeb->fsToWeb(__DIR__ . '/testfile');

    $this->assertEquals('/testfile', $converted);
  }

  /**
   * Test fsToWeb when constructed with a web root with no trailing slash and
   * a doc root with no trailing slash.
   */
  public function testWebNoSlashDocNoSlash() {
    $fsToWeb = new FsToWebPathConverter(__DIR__, '/~user');
    $converted = $fsToWeb->fsToWeb(__DIR__ . '/testfile');

    $this->assertEquals('/~user/testfile', $converted);
  }

  /**
   * Test fsToWeb when constructed with a web root with no trailing slash and
   * a doc root with a trailing slash.
   */
  public function testWebNoSlashDocSlash() {
    $fsToWeb = new FsToWebPathConverter(__DIR__ . '/', '/~user');
    $converted = $fsToWeb->fsToWeb(__DIR__ . '/testfile');

    $this->assertEquals('/~user/testfile', $converted);
  }

  /**
   * Test fsToWeb when constructed with a web root with a trailing slash and
   * a doc root with not trailing slash.
   */
  public function testWebSlashDocNoSlash() {
    $fsToWeb = new FsToWebPathConverter(__DIR__, '/~user/');
    $converted = $fsToWeb->fsToWeb(__DIR__ . '/testfile');

    $this->assertEquals('/~user/testfile', $converted);
  }

  /**
   * Test fsToWeb when constructed with a web root with a trailing slash and
   * a doc root with a trailing slash.
   */
  public function testWebSlashDocSlash() {
    $fsToWeb = new FsToWebPathConverter(__DIR__ . '/', '/~user/');
    $converted = $fsToWeb->fsToWeb(__DIR__ . '/testfile');

    $this->assertEquals('/~user/testfile', $converted);
  }

  /**
   * Test fsToWeb when given a path not in the document root.
   *
   * @expectedException reed\Exception
   */
  public function testConvertPathNotInDocRoot() {
    $fsToWeb = new FsToWebPathConverter(__DIR__);
    $converted = $fsToWeb->fsToWeb('/tmp/testfile');
  }
}
