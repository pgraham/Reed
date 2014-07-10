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
 * This class provides command line option and argument parsing.
 *
 * Currently only supports long option format (--option[=value]) and non-option
 * arguments.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Cmdln
{

	const LONG_OPT_RE = '/--([^=]+)(?:=(\S+))?/';

	private $cmd;
	private $opts = [];
	private $args = [];

	/**
	 * Parse the given command line options. The given array is expected to be in
	 * the same format as $argv would be when running from the cli. This means
	 * that the array is numerically indexed and the first element is the name of
	 * the command. If available, the number of arguments in argv array can be
	 * passed as the second parameter. This avoids counting the elements in the
	 * array, but be warned that if a value is provided it is not validated.
	 *
	 * @param array $argv
	 * @param int   $argc
	 */
	public function __construct(array $argv, $argc = null) {
		if ($argc === null) {
			$argc = count($argv);
		}

		$this->cmd = array_shift($argv);

		foreach ($argv as $arg) {
			if (preg_match(self::LONG_OPT_RE, $arg, $matches)) {
				$optName = $matches[1];
				if (isset($matches[2])) {
					$optVal = $matches[2];
				} else {
					$optVal = true;
				}
				$this->opts[$optName] = new CmdlnOption($optName, $optVal);

			} else {
				$this->args[] = $arg;
			}
		}
	}

	public function hasOption($opt) {
		return isset($this->opts[$opt]);
	}

	public function option($opt) {
		if (!$this->hasOption($opt)) {
			$this->opts[$opt] = new CmdlnOption($opt, false);
		}
		return $this->opts[$opt];
	}
}
