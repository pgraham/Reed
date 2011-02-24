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

use \reed\Exception;

/**
 * This class loads a PHP template into which it substitutes given values.
 *
 * @author Philip Graham
 * @package reed/generator
 */
class CodeTemplateLoader {

  const JOIN_REGEX     = '/\$\{join:([^:]+):([^\}]+)\}/';
  const EACH_REGEX     = '/^([\t ]*)\$\{each:([^\}]+)\}/m';
  const TEMPLATE_REGEX = '/^([\t ]*)\$\{template:([^\}]+)\}/m';
  const TAG_REGEX      = '/\$\{([^\}]+)}/';

  /* Cache of instances keyed by base path. */
  private static $_cache = Array();

  /**
   * Get a (possible cached) instance of a template loader for the given
   * directory.  Using this method improves the caching of loaded templates to
   * be directory specific.  This is on top of the caching provided by the
   * instances themselves.
   *
   * TODO - Move this into a factory class which is injected where needed
   *
   * @param string $dir The base directory where template are to be loaded from.
   * @return TemplateLoader
   */
  public static function get($dir) {
    if (!isset(self::$_cache[$dir])) {
      self::$_cache[$dir] = new CodeTemplateLoader($dir);
    }
    return self::$_cache[$dir];
  }

  /*
   * ===========================================================================
   * Instance
   * ===========================================================================
   */

  /* The base path for where templates are located. */
  private $_basePath;

  /* Cache of previously loaded templates */
  private $_loaded = Array();

  /**
   * Create a new template loader for loading templates located in the directory
   * specified by the given path.
   *
   * @param string $basePath Path to the directory where template are located.
   */
  public function __construct($basePath) {
    $this->_basePath = $basePath;
  }

  /**
   * Loads the specified template into which it substitutes the given values.
   * The templateValues is an array of key - value pairs with the key being
   * a substitution tag and the value being the value with which to replace the
   * substitution tag.  Historically, the key was expected to be the full tag
   * (${tagname}).  This behaviour is deprecated and going forward the keys will
   * be expected to not have the ${} characters of the substitution tag.  For
   * now either syntax is accepted.
   *
   * @param string $templateName
   * @param array $templateValues
   */
  public function load($templateName, Array $templateValues) {
    if (!isset($this->_loaded[$templateName])) {
      $this->_loaded[$templateName] = $this->_load($templateName);
    }

    $template = $this->_loaded[$templateName];
    return $template->forValues($templateValues);
  }

  /* Load the contents of the template file with the given name */
  private function _load($templateName) {
    $templatePath = $this->_basePath . "/$templateName.template";
    if (!file_exists($templatePath)) {
      throw new Exception(
        "Unable to load template: $templatePath does not exist");
    }
    $file = file_get_contents($templatePath);
    $template = new CodeTemplate($file);

    // Get joins
    $joins = Array();
    preg_match_all(self::JOIN_REGEX, $file, $joins, PREG_SET_ORDER);
    foreach ($joins AS $join) {
      $name = $join[1];
      $glue = $join[2];
      $template->addJoin($name, $glue);
    }

    // Get templates
    $subTemplates = Array();
    preg_match_all(self::TEMPLATE_REGEX, $file, $templates, PREG_SET_ORDER);
    foreach ($subTemplates AS $subTemplate) {
      $indent = $subTemplate[1];
      $name   = $subTemplate[2];
      $template->addSubTemplate($name, $indent);
    }

    // Get eaches
    $eaches = Array();
    preg_match_all(self::EACH_REGEX, $file, $eaches, PREG_SET_ORDER);
    foreach ($eaches AS $each) {
      $indent = $each[1];
      $name   = $each[2];
      $template->addEach($name, $indent);
    }

    $tags = Array();
    preg_match_all(self::TAG_REGEX, $file, $tags, PREG_SET_ORDER);
    foreach ($tags AS $tag) {
      if (substr($tag[1], 0, 5) == 'join:') {
        continue;
      }

      if (substr($tag[1], 0, 5) == 'each:') {
        continue;
      }

      if (substr($tag[1], 0, 9) == 'template:') {
        continue;
      }

      $template->addTag($tag[1]);
    }

    return $template;
  }
}
