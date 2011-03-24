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
 * This class encapsulates the an if-block in a code template.  An if-block
 * consists of an 'if' clause, zero or more 'elseif' clauses and an optional
 * 'else' clause.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class IfBlock {

  /* The if-block's else clause */
  private $_else;

  /* THe if-block's else if clauses */
  private $_elseifs = Array();

  /* The id for this if block */
  private $_id;

  /* The if-block's opening if clause */
  private $_if;

  /* The indentation for the if block */
  private $_indent;

  /**
   * Create a new if-block.
   *
   * @param mixed $id The id of this if block.  This should be unique among all
   *   IfBlocks for a particular template.
   * @param string $indent The indentation string for each line of the
   *   substituted value of the if block.
   */
  public function __construct($id, $indent) {
    $this->_id = $id;
    $this->_indent = $indent;
  }

  /**
   * And ElseIf clause to the if block.
   *
   * @param ElseIfClause $elseif
   */
  public function addElseIf(ElseIfClause $elseif) {
    $this->_elseifs[] = $elseif;
  }

  /**
   * Get the block of code that should be substituted for the given set of
   * substitution values.
   *
   * @param Array $values
   * @return The code block for the first clause who's expression is satisfied
   *   by the given values of an empty string no clauses are satified.  If an
   *   else clause has been set then it will always be satisfied.
   */
  public function forValues(Array $values) {
    if (isset($this->_if)) {
      if ($this->_if->isSatisfiedBy($values)) {
        return $this->_outputCode($this->_if->getCode());
      }
    }

    foreach ($this->_elseifs AS $elseif) {
      if ($elseif->isSatisfiedBy($values)) {
        return $this->_outputCode($elseif->getCode());
      }
    }

    if (isset($this->_else)) {
      return $this->_outputCode($this->_else->getCode());
    }

    return '';
  }

  /**
   * Getter for the IfBlock's id.
   *
   * @return mixed $id Whatever was passed into the constructor.
   */
  public function getId() {
    return $this->_id;
  }

  /**
   * Set the IfBlock's ElseClause.
   *
   * @param ElseClause $else
   */
  public function setElse($else) {
    $this->_else = $else;
  }

  /**
   * Set the IfBlock's IfClause.
   *
   * @param IfClause $ifClause
   */
  public function setIf($if) {
    $this->_if = $if;
  }

  /*
   * Formats the given code so that it is indented the same as the declared if
   * block.
   */
  private function _outputCode($code) {
    return preg_replace('/\n\s*/m', "\n" . $this->_indent, trim($code));
  }
}
