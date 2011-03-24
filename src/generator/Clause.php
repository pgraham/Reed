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
 * Base class for all IfBlock clauses.
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package reed/generator
 */
abstract class Clause {

  /* The code that is output if the expression for this clause is satisfied */
  protected $_code;

  /**
   * Get the code that is output if the expression for this clause is satisfied.
   *
   * @return string
   */
  public function getCode() {
    return $this->_code;
  }

  /**
   * Set the code that is output if the expression for this clause is satisfied.
   *
   * @param string $code
   */
  public function setCode($code) {
    $this->_code = trim($code);
  }
}
