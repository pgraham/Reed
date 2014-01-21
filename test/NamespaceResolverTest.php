<?php
/**
 * =============================================================================
 * Copyright (c) 2013, Philip Graham
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
namespace zpt\util\test;

use \Composer\Autoload\ClassLoader;
use \PHPUnit_Framework_TestCase as TestCase;
use \zpt\util\NamespaceResolver;

require_once __DIR__ . '/test-common.php';

/**
 * Test the NamespaceResolver class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class NamespaceResolverTest extends TestCase
{

	private $resolver;

	private $baseDir;
	private $vendorDir;

	protected function setUp() {
		$baseDir = __DIR__ . '/ns-base';
		$vendorDir = $baseDir . DIRECTORY_SEPARATOR . 'vendor';

		if (file_exists($baseDir)) {
			exec("rm -r $baseDir");
		}

		mkdir("$baseDir/src/baz", 0755, true);
		mkdir("$baseDir/zpt/fux/fax", 0755, true);
		mkdir("$vendorDir/zeptech/foo/src/bar", 0755, true);
		mkdir("$vendorDir/zeptech/baz/zpt/baz/fux", 0755, true);

		$loader = new ClassLoader();
		$loader->setPsr4('zpt\\foo\\', array($vendorDir . '/zeptech/foo/src'));
		$loader->setPsr4('zpt\\bar\\', array($baseDir . '/src'));
		$loader->set('zpt\\baz', array($vendorDir . '/zeptech/baz'));
		$loader->set('zpt\\fux', array($baseDir . '/'));

		$this->resolver = new NamespaceResolver($loader);
		$this->baseDir = $baseDir;
		$this->vendorDir = $vendorDir;
	}

	public function testPsr4VendorNamespace() {
		$nsPath = $this->resolver->ResolveNamespace('zpt\\foo');
		$this->assertEquals($this->vendorDir . '/zeptech/foo/src', $nsPath);
	}

	public function testPsr4VendorSubNamespace() {
		$nsPath = $this->resolver->ResolveNamespace('zpt\\foo\\bar');
		$this->assertEquals($this->vendorDir . '/zeptech/foo/src/bar', $nsPath);
	}

	public function testPsr4BaseNamespace() {
		$nsPath = $this->resolver->ResolveNamespace('zpt\\bar');
		$this->assertEquals($this->baseDir . '/src', $nsPath);
	}

	public function testPsr4BaseSubNamespace() {
		$nsPath = $this->resolver->ResolveNamespace('zpt\\bar\\baz');
		$this->assertEquals($this->baseDir . '/src/baz', $nsPath);
	}

	public function testPsr0VendorNamespace() {
		$nsPath = $this->resolver->ResolveNamespace('zpt\\baz');
		$this->assertEquals($this->vendorDir . '/zeptech/baz/zpt/baz', $nsPath);
	}

	public function testPsr0VendorSubNamespace() {
		$nsPath = $this->resolver->ResolveNamespace('zpt\\baz\\fux');
		$this->assertEquals($this->vendorDir . '/zeptech/baz/zpt/baz/fux', $nsPath);
	}

	public function testPsr0BaseNamespace() {
		$nsPath = $this->resolver->ResolveNamespace('zpt\\fux');
		$this->assertEquals($this->baseDir . '/zpt/fux', $nsPath);
	}

	public function testPsr0BaseSubNamespace() {
		$nsPath = $this->resolver->ResolveNamespace('zpt\\fux\\fax');
		$this->assertEquals($this->baseDir . '/zpt/fux/fax', $nsPath);
	}
}
