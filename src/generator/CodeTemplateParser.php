<?php
/**
 * =============================================================================
 * Copyright (c) 2011, Philip Graham
 * All rights reserved.
 *
 * This file is part of Clarinet and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @package reed/generator
 */
namespace reed\generator;

/**
 * This class parses a code template into a object structure appropriate for
 * substitution.
 *
 * @author Philip Graham
 * @package reed/generator
 */
class CodeTemplateParser {

  const JOIN_REGEX     = '/\$\{join:([^:]+):([^\}]+)\}/';
  const JSON_REGEX     = '/\$\{json:([^\}]+)\}/';

  const TAG_REGEX      = '/\$\{([^\}]+)}/';

  /**
   * Parse the given code and populate the given CodeTemplate.
   *
   * @param string $code The code to parse.
   * @param CodeTemplate $template The template to populate.
   */
  public function parse($code, CodeTemplate $template) {

    // Parse If blocks
    $ifParser = new IfParser($this);
    $parsed = $ifParser->parse($code, $template);

    // Parse Each blocks
    $eachParser = new EachParser($this);
    $parsed = $eachParser->parse($parsed, $template);

    // Get joins
    $joins = Array();
    preg_match_all(self::JOIN_REGEX, $parsed, $joins, PREG_SET_ORDER);
    foreach ($joins AS $join) {
      $name = $join[1];
      $glue = $join[2];
      $template->addJoin($name, $glue);
    }

    // Get JSON outputs
    $jsons = Array();
    preg_match_all(self::JSON_REGEX, $parsed, $jsons, PREG_SET_ORDER);
    foreach ($jsons AS $json) {
      $name = $json[1];
      $template->addJson($name);
    }

    $tags = Array();
    preg_match_all(self::TAG_REGEX, $parsed, $tags, PREG_SET_ORDER);
    foreach ($tags AS $tag) {
      if (preg_match('/^\$\{if\d+\}$/', $tag[0])) {
        continue;
      }

      if (preg_match('/^\$\{each\d+\}$/', $tag[0])) {
        continue;
      }

      if (substr($tag[1], 0, 5) === 'join:') {
        continue;
      }

      if (substr($tag[1], 0, 5) === 'json:') {
        continue;
      }

      $template->addTag($tag[1]);
    }
    return $parsed;
  }
}
