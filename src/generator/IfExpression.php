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
 * TODO - Abstract expression evaluation then have this class consume a set of
 *        supported operators
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

  /* The operator to use for evaluating the expression */
  private $_operator;

  /**
   * Create a new IfExpression.
   *
   * @param string $expression Unparsed expression string.
   */
  public function __construct($expression) {
    /*
    foreach ($this->_evaluators AS $evaluator) {
      $evaluator->canEvaluate($expression);
      $this->_evaluator = $evaluator;
    }

    if ($this->_evaluator === null) {
      $this->_evaluator = new BooleanEvaluator(true);
    }
    */

    if (strpos($expression, '=') !== false) {
      $parts = explode('=', $expression, 2);
      $this->_name = trim($parts[0]);
      $this->_value = trim($parts[1]);
      $this->_operator = '=';
    } else if (strpos($expression, '>') !== false) {
      $parts = explode('>', $expression, 2);
      $this->_name = trim($parts[0]);
      $this->_value = trim($parts[1]);
      $this->_operator = '>';
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
    if (!isset($values[$this->_name])) {
      return false;
    }

    if ($this->_value === null) {
      return $values[$this->_name] === true;
    }

    $val = $values[$this->_name];
    if ($this->_operator === '=') {
      return $val == $this->_value;

    } else if ($this->_operator === '>') {
      return $val > $this->_value;

    }
  }
}
