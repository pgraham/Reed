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
 * This class encapsulates expression evaluation for a conditional clause of an
 * IfBlock.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class IfExpression {

  /*
   * The name of the value against which the expression is evaluated.
   */
  private $_name;

  /*
   * The expected value of the value with the specified name.  If this is null
   * then the expression will be true if the value of the specifed value is a
   * boolean true.
   */
  private $_value;

  /**
   * Create a new IfExpression.
   *
   * @param string $expression Unparsed expression string.
   */
  public function __construct($expression) {
    if (strpos($expression, '=') !== false) {
      list($this->_name, $this->_value) = explode('=', $expression, 2);
    } else {
      $this->_name = $expression;
    }
  }

  /**
   * Determines whether or not the encapsulated expression evaluates to true for
   * the given set of values.
   *
   * @param Array $values Set of substitution values.
   * @return boolean
   */
  public function isSatisfiedBy(Array $values) {
    if ($this->_value === null) {
      return isset($values[$this->_name]) && $values[$this->_name] === true;
    } else {
      return isset($values[$this->_name]) &&
        $values[$this->_name] == $this->_value;
    }
  }
}
