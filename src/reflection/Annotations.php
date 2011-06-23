<?php
/**
 * =============================================================================
 * Copyright (c) 2011, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace reed\reflection;

use \ArrayAccess;
use \Reflector;

use \reed\Exception;

/**
 * This class parses a given Reflector for annotations and provides array style
 * or method style access to them.  Only Reflectors that implement the
 * getDocComment method are supported.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Annotations implements ArrayAccess {

  /*
   * The reflection element's annotations as returned by
   * ReflectionHelper::getAnnotations().
   */
  private $_annotations;

  /**
   * Create a new Annotations instance for the given reflection element.
   * Ommiting the Reflector will create an empty annotations object.
   *
   * @param Reflector $reflector The object from which to parse annotations.
   * @throws Exception If the given object does not contain a getDocComment()
   *   method.
   */
  public function __construct(Reflector $reflector = null) {
    if ($reflector === null) {
      $this->_annotations = array();
      return;
    }

    if (!method_exists($reflector, 'getDocComment')) {
      throw new Exception("Only Reflector implementations that provide a"
        . " getDocComment() method can be parsed for annotations.");
    }

    $this->_annotations = ReflectionHelper::getAnnotations(
      $reflector->getDocComment());
  }

  /*
   * ===========================================================================
   * ArrayAccess implementation
   * ===========================================================================
   */

  /**
   * Whether or not the requested annotation exists.
   *
   * @param mixed $offset
   * @return boolean
   */
  public function offsetExists($offset) {
    return isset($this->_annotations[$offset]);
  }

  /**
   * Get the information for the requested annotation.
   *
   * @param mixed $offset
   * @return mixed
   */
  public function offsetGet($offset) {
    return $this->_annotations[$offset];
  }

  /**
   * Set the given annotation to be the given value.
   *
   * @param mixed $offset
   * @param mixed $value
   */
  public function offsetSet($offset, $value) {
    // TODO Should this be allowed?  Does allowing this cause an expectation
    // that were the same doc commenct parsed again the value would still be
    // different?
    if ($offset === null) {
      $this->_annotations[] = $value;
    } else {
      $this->_annotations[$offset] = $value;
    }
  }

  /**
   * Unset the annotation at the given offset.
   *
   * @param mixed $offset
   */
  public function offsetUnset($offset) {
    // TODO Should this be allowed?  Does allowing this cause an expectation
    // that were the same doc commenct parsed again the value would still be
    // different?
    unset($this->_annotations[$offset]);
  }
}
