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
 * This class tests the {@link CmdlnOption} class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class CmdlnOptionTest extends TestCase
{

	public function testIsTrue() {
		$cmdlnOpt = new CmdlnOption('option', false);
		$this->assertFalse($cmdlnOpt->isTrue());

		$cmdlnOpt = new CmdlnOption('option', true);
		$this->assertTrue($cmdlnOpt->isTrue());

		$cmdlnOpt = new CmdlnOption('option', 'true');
		$this->assertFalse($cmdlnOpt->isTrue());
	}

}
