<?php
/**
 * =============================================================================
 * Copyright (c) 2010, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under the
 * 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace reed;

/**
 * Generic autoloader class which performs a namespace -> filesystem mapping to
 * autoload classes.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class ClassLoader {

  /**
   * Register a new autoloader for the given basepath and namespace.
   *
   * @param string $basePath The path where classes in the base namespace are
   *   found.
   * @param string $ns The base namespace for which the loader loads classes.
   */
  public static function register($basePath, $ns) {
    new ClassLoader($basePath, $ns);
  }

  /*
   * ===========================================================================
   * Instance
   * ===========================================================================
   */

  private $_basePath;
  private $_ns;
  private $_nsLen;

  /**
   * Create a new class autoloader.
   *
   * @param string $basePath The path where classes in the base namespace are
   *   found.
   * @param string $ns The base namespace for which this loader loads classes.
   */
  protected function __construct($basePath, $ns) {
    $this->_basePath = $basePath;
    $this->_ns = $ns;
    $this->_nsLen = strlen($ns);

    // Register the instance as an autoloader
    spl_autoload_register(array($this, 'loadClass'));
  }

  /**
   * The autoload function.
   *
   * @param string $className The name of the class to load.
   */
  public function loadClass($className) {
    if (substr($className, 0, $this->_nsLen) != $this->_ns) {
      return;
    }

    $logicalPath = str_replace('\\', DIRECTORY_SEPARATOR,
      substr($className, $this->_nsLen));
    $fullPath = $this->_basePath . DIRECTORY_SEPARATOR . "$logicalPath.php";
    if (file_exists($fullPath)) {
      // There is no need to require_once here since the autoloader will only
      // be invoked the first time the class is referenced.
      require $fullPath;
    }
  }
}
