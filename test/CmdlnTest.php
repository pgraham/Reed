<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed. For the full copyright and license information
 * please view the LICENSE file that was distributed with this source code.
 */
namespace zpt\util;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * Test Cmdln option and argument parsing.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class CmdlnTest extends TestCase
{

	public function testParseBooleanOption() {
		$cmdln = new Cmdln([ '/usr/bin/php', '--version' ], 2);

		$this->assertTrue($cmdln->hasOption('version'));

		$opt = $cmdln->option('version');
		$this->assertInstanceOf('zpt\util\CmdlnOption', $opt);
		$this->assertTrue($opt->isTrue());
	}

}
