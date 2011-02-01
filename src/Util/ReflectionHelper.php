<?php
namespace Reed\Util;

class ReflectionHelper {

  public static function getAnnotations($docComment) {
    $annoRegexp = '/@(\w+)(.*)\n/';
    $paramRegexp = '/(\w+)\s*=([^,)]*)(?:,|\))/';

    $matches = array();
    preg_match_all($annoRegexp, $docComment, $matches, PREG_SET_ORDER);

    $annotations = array();
    foreach ($matches AS $anno) {
      $annoName = strtolower($anno[1]);
      $annotations[$annoName] = array();

      $params = array();
      preg_match_all($paramRegexp, $anno[2], $params, PREG_SET_ORDER);
      foreach ($params AS $param) {
        $annotations[$annoName][$param[1]] = self::_parseValue($param[2]);
      }
    }
    return $annotations;
  }

  private static function _parseValue($value) {
    $val = trim($value);

    if (strtolower($val) == 'true') {
      return true;
    } else if (strtolower($val) == 'false') {
      return false;
    } else {
      return $val;
    }
  }
}
