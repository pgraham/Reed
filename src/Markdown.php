<?php
/**
 * Copyright (c) 2012, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zpt\util;

if (stream_resolve_include_path('markdown.php') !== false) {
  include_once 'markdown.php';
}

/**
 * This class parses Markdown text into HTML if the Markdown PHP extension is
 * installed.
 *
 * http://michelf.ca/projects/php-markdown/
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Markdown {

  /**
   * Parse the given markdown string.
   *
   * @param string $md The Markdown string to convert to HTML.
   * @return string The converted string.
   */
  public static function parse($md) {
    if (!defined('MARKDOWN_VERSION')) {
      // Markdown is not available so simply return the unparsed string
      // TODO Log a warning
      return $md;
    }

    return Markdown($md);
  }

  /**
   * Parse the given markdown string and strip any wrapping block elements.
   *
   * @param string $md The Markdown string to convert to HTML.
   * @return string The converted string without any enclosing block elements.
   */
  public static function parseInline($md) {
    $parsed = self::parse($md);

    if (preg_match('/^<p>(.+)<\/p>$/', $parsed, $matches)) {
      return $matches[1];
    }
    return $parsed;
  }

}
