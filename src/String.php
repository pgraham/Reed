<?php
/**
 * Copyright (c) 2012, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under
 * the 3-clause BSD License. The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zpt\util;

/**
 * Immutable string wrapper. Require prim-wrap.php file in the same directory as
 * this class to provide a global String() function for creating instances of
 * this class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class String
{

	private $str;

	/**
	 * Create a new string wrapper object.
	 *
	 * @param string $str
	 */
	public function __construct($str)
	{
		$this->str = $str;
	}

	/**
	 * toString implementation simply returns the encapsulated string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->str;
	}

	/**
	 * Return a boolean indicating if the encapsulated string ends with the
	 * given suffix.
	 *
	 * @return boolean
	 */
	public function endsWith($suffix)
	{
		return StringUtils::endsWith($this->str, $suffix);
	}

	/**
	 * Returns a new String object wrapping the result of passing the
	 * encapsulated string through StringUtils::format with the given
	 * parameters.
	 *
	 * @return String
	 */
	public function format()
	{
		$args = func_get_args();
		array_unshift($args, $this->str);
		$formatted = call_user_func_array(
			'zpt\util\StringUtils::format',
			$args
		);
		return new String($formatted);
	}

	/**
	 * Returns a new String wrapper for the result of passing the encapsulated
	 * string through StringUtils::fromCamelCase().
	 *
	 * @param string $separator
	 *   The character(s) to insert between each of the camel cased words.
	 *   Default: _
	 * @param integer $flags
	 *   Flags for controlling the output string.  See the related constants in
	 *   the StringUtils class for more information.
	 * @return String
	 */
	public function fromCamelCase($separator = '_', $flags = null)
	{
		$fromCamelCase = StringUtils::fromCamelCase(
			$this->str,
			$separator,
			$flags
		);
		return new String($fromCamelCase);
	}

	/**
	 * Whether or not the represented string is the empty string.
	 *
	 * @return boolean.
	 */
	public function isEmpty() {
		return $this->str === '';
	}

	/**
	 * Joins this string with a given string, optionally separated by 
	 * a specified string.
	 *
	 * @param string $join
	 * @param string $separator
	 * @return String
	 */
	public function join($join, $separator = '') {
		$joined = StringUtils::join($this->str, $join, $separator);
		return new String($joined);
	}

	/**
	 * Strip whitespace or other characters from the beginning of the string.
	 *
	 * @param string $characterMask
	 *   List of characters to be removed
	 * @return String
	 */
	public function ltrim($characterMask = null) {
		if ($characterMask) {
			return new String(ltrim($this->str, $characterMask));
		} else {
			return new String(ltrim($this->str));
		}
	}

	/**
	 * Return a boolean indicating if the encapsulated string starts with the
	 * given prefix.
	 *
	 * @return boolean
	 */
	public function startsWith($prefix) {
		return StringUtils::startsWith($this->str, $prefix);
	}

	/**
	 * Strips the specified prefix from the string. If the string does not start 
	 * with the prefix then the same String is returned.
	 *
	 * @param string $prefix
	 */
	public function stripStart($prefix) {
		$stripped = StringUtils::stripStart($this->str, $prefix);
		return new String($stripped);
	}

	/**
	 * Returns a new String wrapper for the result of passing the encapsulated
	 * string through StringUtils::toCamelCase().
	 *
	 * @param string $separator
	 *   The character(s) that are separating the words in the string.
	 *   Default: _
	 * @param boolean $studly
	 *   Whether or not to upper case the first character of the string.
	 *   Default: false
	 */
	public function toCamelCase($separator = '_', $studly = false)
	{
		$camelCased = StringUtils::toCamelCase($this->str, $separator, $studly);
		return new String($camelCased);
	}

	/**
	 * Returns a new trimmed String wrapper.
	 *
	 * @param string $charMask
	 * @return String
	 */
	public function trim($charMask = null) {
		if ($charMask === null) {
			return new String(trim($this->str));
		} else {
			return new String(trim($this->str, $charMask));
		}
	}
}
