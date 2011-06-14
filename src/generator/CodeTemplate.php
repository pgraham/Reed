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
 * This class encapsulates a parsed code template.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class CodeTemplate extends CodeBlock {

  /* The each substitution tags */
  private $_eaches = Array();

  /* The template's if blocks */
  private $_ifs = Array();

  /**
   * Create a new code template for the given text.
   *
   * @param string $template
   */
  public function __construct() {
    // Initialize the parent with no indent
    parent::__construct('');
  }

  /**
   * Add an each to the template.
   *
   * @param EachBlock $each
   */
  public function addEach($each) {
    $this->_eaches[] = $each;
  }

  /**
   * Add an if block to the template.
   *
   * @param IfBlock $ifBlock Encapsulated if block.
   */
  public function addIf(IfBlock $ifBlock) {
    $this->_ifs[] = $ifBlock;
  }

  /**
   * Substitute the given values into the template and return the result.
   *
   * @param array $values The substitution values.
   * @return string The template with values substituted for tags.
   */
  public function forValues(array $values, $code = null) {
    if ($code === null) {
      $code = $this->_code;
    }

    // Do the if replacement first since the if code may contain other
    // substitutions
    if (count($this->_ifs) > 0) {
      // The if statements should have been added in an order that will
      // make it possible to loop through the array once with nested ifs
      // already substituted in by the time they are reached
      foreach ($this->_ifs AS $ifBlock) {
        $toReplace    = "\${if{$ifBlock->getId()}}";
        $replacement = $ifBlock->forValues($values);

        $code = str_replace($toReplace, $replacement, $code);
      }
    }

    if (count($this->_eaches) > 0) {
      foreach ($this->_eaches AS $eachBlock) {
        $toReplace = $eachBlock->getTag();
        $replacement = $eachBlock->forValues($values);
        
        $code = str_replace($toReplace, $replacement, $code);
      }
    }
    
    return parent::forValues($values, $code);
  }
}
