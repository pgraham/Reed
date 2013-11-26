<?php
/**
 * Copyright (c) 2012, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under
 * the 3-clause BSD License.	The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zpt\util;

/**
 * This class provides various string utility functions.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class StringUtils {

	/**
	 * Flag for lower casing the first character of a string converted from or to
	 * camel case.
	 */
	const FIRST_TO_LOWER = 1;

	/**
	 * Flag for upper casing the first character of a string converted from or to
	 * camel case.
	 */
	const FIRST_TO_UPPER = 2;

	/**
	 * Flag for upper casing the first character of each word in string converted
	 * from camel case.  The will be overridden by FIRST_TO_LOWER for the first
	 * character.
	 */
	const CAPITALIZE_WORDS = 4;

	/**
	 * Flag for lower casing the entire string.  This will override all other
	 * flags except FIRST_TO_UPPER.
	 */
	const STRING_TO_LOWER = 8;

	/**
	 * Returns true with the given string ends with the specified suffix.
	 *
	 * @param string $str
	 * @param string $suffix
	 */
	public static function endsWith($str, $suffix) {
		return substr($str, strlen($str) - strlen($suffix)) === $suffix;
	}

	/**
	 * Format a string using {#} style replacements.
	 *
	 * @param string $format
	 * @param string...
	 */
	public static function format($format) {
		$args = func_get_args();
		array_shift($args);

		$named = [];
		if (is_array($args[0])) {
			$named = array_shift($args);
		}

		$getIdxArg = function ($matches) use ($args) {
			$idx = (int) (substr($matches[0], 1, -1));
			return $args[$idx];
		};

		$getNamedArg = function ($matches) use ($named) {
			$idx = substr($matches[0], 1, -1);
			if (isset($named[$idx])) {
				return $named[$idx];
			} else {
				return $matches[0];
			}
		};

		$fmtd = preg_replace_callback('/(\{\d+\})/', $getIdxArg, $format);
		$fmtd = preg_replace_callback('/(\{\w+\})/', $getNamedArg, $fmtd);
		return $fmtd;
	}

	/**
	 * Separate a camel cased string with the given string.  The returned string
	 * is lower cased.
	 *
	 * @param string $camelCased The camel cased string to convert.
	 * @param string $separator The string to insert between each of the camel
	 *   cased words. Default: _
	 * @param boolean $flags Flags for changing the outputted string after the
	 *   conversion.	See class constants for supported strings.
	 *   Default: lower case the entire string
	 * @return string The camel cased string with the words separated by the given
	 *   separator and lower cased.
	 */
	public static function fromCamelCase($camelCased, $separator = '_',
		$flags = null)
	{

		if ($flags === null) {
			$flags = self::STRING_TO_LOWER;
		}

		$firstToLower = (boolean) ($flags & self::FIRST_TO_LOWER);
		$firstToUpper = (boolean) ($flags & self::FIRST_TO_UPPER);
		$capitalizeWords = (boolean) ($flags & self::CAPITALIZE_WORDS);
		$stringToLower = (boolean) ($flags & self::STRING_TO_LOWER);

		// Since the default is specified as lower casing the entire string, force
		// the STRING_TO_LOWER flag unless CAPITALIZE_WORDS is specified.
		if (!$capitalizeWords) {
			$stringToLower = true;
		}

		if ($capitalizeWords) {
			$separated = strtoupper($camelCased[0]);
		} else {
			$separated = $camelCased[0];
		}

		$cb = function ($match) use ($separator, $capitalizeWords) {
			return $separator . $match[0];
		};

		$separated .= preg_replace_callback('/[A-Z]/', $cb, substr($camelCased, 1));

		// Enforce flags
		if ($firstToLower) {
			$separated = strtolower($separated[0]) . substr($separated, 1);
		}

		if ($stringToLower) {
			$separated = strtolower($separated);
		}

		if ($firstToUpper) {
			$separated = strtoupper($separated[0]) . substr($separated, 1);
		}

		return $separated;
	}

	/**
	 * Joins a string with another string, optionally separated by another string.
	 *
	 * @param string $left
	 * @param string $right
	 * @param string $separator [optional]
	 */
	public static function join($left, $right, $separator = '') {
			if (!$right) {
					return $left;
			}

			return $left . $separator . $right;
	}

	/**
	 * Convert a string of words separated by the given separator to a camel cased
	 * string.
	 *
	 * @param string $separated The string to convert.
	 * @param string $separator The string separating the words in the string to
	 *   convert.  Default: _
	 * @param boolean $firstToUpper Whether or not to upper case the first
	 *   character of the string.
	 * @return string A camelCased version of the separated string.
	 */
	public static function toCamelCase($separated, $separator = '_',
		$firstToUpper = false)
	{
		if ($firstToUpper) {
			$camelCased = strtoupper($separated[0]);
		} else {
			$camelCased = $separated[0];
		}

		$regexp = '/' . preg_quote($separator, '/') . '(\w)/';
		$camelCased .= preg_replace_callback($regexp, function ($match) {
			return strtoupper($match[1]);
		}, substr($separated, 1));

		return $camelCased;
	}

	/**
	 * Format an array of key-value pairs for use as the query string portion of a
	 * URL.
	 */
	public static function urlEncode(array $params) {
		$qs = array();

		foreach ($params as $key => $value) {
			$qs[] = urlencode($key) . '=' . urlencode($value);
		}

		return implode('&', $qs);
	}
}
