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
 */
namespace reed\generator;

/**
 * This class parses a code block for substitution tags.
 *
 * @author Philip Graham
 */
class CodeBlockParser {

  const TAG_REGEX      = '/\$\{([^\}]+)}/';
  const JOIN_REGEX     = '/\$\{join:([^:]+):([^\}]+)\}/';
  const JSON_REGEX     = '/\$\{json:([^\}]+)\}/';

  /**
   * Parse a given code block for substitution tags.
   */
  public function parse(CodeBlock $block) {
    $code = $block->getCode();

    // Get joins
    $joins = Array();
    preg_match_all(self::JOIN_REGEX, $code, $joins, PREG_SET_ORDER);
    foreach ($joins AS $join) {
      $name = $join[1];
      $glue = $join[2];
      $block->addJoin($name, $glue);
    }

    // Get JSON outputs
    $jsons = Array();
    preg_match_all(self::JSON_REGEX, $code, $jsons, PREG_SET_ORDER);
    foreach ($jsons AS $json) {
      $name = $json[1];
      $block->addJson($name);
    }

    $tags = Array();
    preg_match_all(self::TAG_REGEX, $code, $tags, PREG_SET_ORDER);
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

      $block->addTag($tag[1]);
    }
  }
}
