<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed. For the full copyright and license information
 * please view the LICENSE file that was distributed with this source code.
 */
namespace zpt\util;

/**
 * This class encapsulates a command line option value.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class CmdlnOption
{

	private $name;
	private $value;

	/**
	 * Create a new option with the given name and value.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}

	public function isTrue() {
		return $this->value === true;
	}
}
