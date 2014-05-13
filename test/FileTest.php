<?php
/**
 * =============================================================================
 * Copyright (c) 2013, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under the
 * 3-clause BSD License.	The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zpt\util\test;

use PHPUnit_Framework_TestCase as TestCase;
use zpt\util\File;

require_once __DIR__ . '/test-common.php';

/**
 * Tests for the {@link File} class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class FileTest extends TestCase {

	protected function tearDown() {
		$dest = __DIR__ . '/files/dest';
		if (file_exists($dest)) {
			exec('rm -r ' . __DIR__ . '/files/dest');
		}
	}

	public function testCopyNoSrc() {
		$this->assertFalse(File::copy(
			__DIR__ . '/files/does/not/exists.txt',
			__DIR__ . '/files/dest.txt'
		));
	}

	public function testCopyFile() {
		$this->assertTrue(File::copy(
			__DIR__ . '/files/src/file.txt',
			__DIR__ . '/files/dest/file.txt'
		));
		$this->assertFileExists(__DIR__ . '/files/dest/file.txt');
	}

	public function testCopyDirShallow() {
		$this->assertTrue(File::copy(
			__DIR__ . '/files/src/shallow',
			__DIR__ . '/files/dest'
		));
		$this->assertFileExists(__DIR__ . '/files/dest');
		$this->assertFileExists(__DIR__ . '/files/dest/file.txt');
	}

	public function testCopyDirEmpty() {
		$src = __DIR__ . '/files/src/empty';
		if (!file_exists($src)) {
			mkdir($src);
		}

		$this->assertTrue(File::copy(
			__DIR__ . '/files/src/empty',
			__DIR__ . '/files/dest'
		));
		$this->assertFileExists(__DIR__ . '/files/dest');
	}

	public function testCopyDirDeep() {
		$this->assertTrue(File::copy(
			__DIR__ . '/files/src/deep',
			__DIR__ . '/files/dest'
		));
		$this->assertFileExists(__DIR__ . '/files/dest');
		$this->assertFileExists(__DIR__ . '/files/dest/file.txt');
		$this->assertFileExists(__DIR__ . '/files/dest/subdir');
		$this->assertFileExists(__DIR__ . '/files/dest/subdir/file.txt');
	}

}
