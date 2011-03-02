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
 * @package reed/util
 */
namespace reed\util;

/**
 * This class provides extensions to PHP's built in reflection capabilities.
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package reed/util
 */
class ReflectionHelper {

  private static $_annoRE = '/@(\w+)\(?(.*)\)?\n/';
  private static $_paramRE = '/(\w+)\s*=\s*(\{[^}]*\}|"[^"]*"|[^,)]*)(?:,|\))/';

  /**
   * Parse any annotations from the given doc comment and return them in an
   * array structure.  The annotations in the returned array are indexed by
   * their lowercased name.  Parameters with a value defined as a comma
   * separated list contained in braces will be return as arrays.  Parameter
   * values defined in quotes will have the quotes stripped and the inner value
   * parsed for boolean and numeric values.  If not a boolean or numeric value,
   * will be return as a string.  All other parameter values will be returned as
   * either a boolean, number or string as appropriate.
   *
   * @param string $docComment The comment to parse.
   * @return array Array containing the defined annotations.
   */
  public static function getAnnotations($docComment) {
    $matches = array();
    preg_match_all(
      self::$_annoRE,
      $docComment,
      $matches,
      PREG_SET_ORDER
    );

    $annotations = array();
    foreach ($matches AS $anno) {
      $annoName = strtolower($anno[1]);
      $annotations[$annoName] = array();

      $params = array();
      preg_match_all(self::$_paramRE, $anno[2], $params, PREG_SET_ORDER);
      foreach ($params AS $param) {
        $annotations[$annoName][$param[1]] = self::_parseValue($param[2]);
      }
    }
    return $annotations;
  }

  private static function _parseValue($value) {
    $val = trim($value);

    if (substr($val, 0, 1) == '{' && substr($val, -1) == '}') {
      // Array values
      $vals = explode(',', substr($val, 1, -1));
      $val = array();
      foreach ($vals AS $v) {
        $val[] = self::_parseValue($v);
      }
      return $val;

    } else if (substr($val, 0, 1) == '"' && substr($val, -1) == '"') {
      // Quoted value, remove the quotes then recursively parse and return
      $val = substr($val, 1, -1);
      return self::_parseValue($val);

    } else if (strtolower($val) == 'true') {
      // Boolean value = true
      return true;

    } else if (strtolower($val) == 'false') {
      // Boolean value = false
      return false;

    } else if (is_numeric($val)) {
      // Numeric value, determine if int or float and then cast
      if ((float) $val == (int) $val) {
        return (int) $val;
      } else {
        return (float) $val;
      }

    } else {
      // Nothing special, just return as a string
      return $val;
    }
  }
}
