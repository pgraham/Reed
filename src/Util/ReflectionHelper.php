<?php
namespace Reed\Util;

class ReflectionHelper {

  private static $_annoRE = '/@(\w+)\(?(.*)\)?\n/';
  private static $_paramRE = '/(\w+)\s*=\s*(\{[^}]*\}|"[^"]*"|[^,)]*)(?:,|\))/';

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
