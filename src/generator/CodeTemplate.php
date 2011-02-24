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
 * This class encapsulates a parsed code template.
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package reed/generator
 */
class CodeTemplate {

  /* The each substitution tags */
  private $_eaches = Array();

  /* The join substitution tags */
  private $_joins = Array();

  /* The sub template tags */
  private $_subTemplates = Array();

  /* The simple substitution tags */
  private $_tags = Array();

  /* The complete template text, into which values are substituted */
  private $_template;

  /**
   * Create a new code template for the given text.
   *
   * @param string $template
   */
  public function __construct($template) {
    $this->_template = $template;
  }

  /**
   * Add an each to the template.
   *
   * @param string $name The name of the value to substitute into the template.
   * @param string $indent The indentation for each line of the each.
   */
  public function addEach($name, $indent) {
    $this->_eaches[] = Array
    (
      'name'   => $name,
      'indent' => $indent
    );
  }

  /**
   * Add a join to the template.
   *
   * @param string $name The name of the value to join together before inserting
   *   into the template.
   * @param string $glue The string with which to glue together the joined
   *   values.
   */
  public function addJoin($name, $glue) {
    $this->_joins[] = Array
    (
      'name' => $name,
      'glue' => $glue
    );
  }

  /**
   * Add a sub-template to the template.
   *
   * @param string $name The name of the value to substitute into the template.
   * @param string $indent The indentation for each line of the template.
   */
  public function addSubTemplate($name, $indent) {
    $this->_subTemplate = Array
    (
      'name'   => $name,
      'indent' => $indent
    );
  }

  /**
   * Add a simple tag to the template.
   *
   * @param string $name The name of the value to substitute into the template.
   */
  public function addTag($name) {
    if (!in_array($name, $this->_tags)) {
      $this->_tags[] = $name;
    }
  }

  /**
   * Substitute the given values into the template and return the result.
   *
   * @param array $values The substitution values.
   * @return string The template with values substituted for tags.
   */
  public function forValues(Array $values) {
    $toReplace = Array();
    $replacements = Array();

    foreach ($values AS $name => $value) {
      if (substr($name, 0, 2) == '${' && substr($name, -1) == '}') {
        $name = substr($name, 2, -1);
      }

      foreach ($this->_joins AS $join) {
        if ($join['name'] != $name) {
          continue;
        }

        $toReplace[] = "\${join:$name:{$join['glue']}}";
        $replacements[] = implode($join['glue'],
          (is_array($value))
            ? $value
            : Array($value)
        );
      }

      foreach ($this->_eaches AS $each) {
        if ($each['name'] != $name) {
          continue;
        }

        $eachVal = str_replace("\n", "\n" . $each['indent'], $value);
        $toReplace[] = "\${each:$name}";
        $replacements[] = implode("\n" . $each['indent'], $eachVal);
      }

      foreach ($this->_subTemplates AS $subTemplate) {
        $subTemplateVal = CodeTemplateLoader::load($name)->forValues($values);
        $subTemplateVal = str_replace("\n", "\n" . $subTemplate['indent'],
          $subTemplateVal);

        $toReplace[] = "\${template:$name}";
        $replacements[] = $subTemplateVal;
      }

      foreach ($this->_tags AS $tag) {
        if ($tag == $name) {
          $toReplace[] = '${' . $tag . '}';
          $replacements[] = $value;
        }
      }
    }

    return str_replace($toReplace, $replacements, $this->_template);
  }
}
