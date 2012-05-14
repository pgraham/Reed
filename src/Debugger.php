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
namespace reed;

/**
 * Class that will conditionally output debug info.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Debugger {

  public static $stdOutAppender;
  public static $errorLogAppender;

  private static $_initialized = false;

  private function _initAppenders() {
    if (self::$_initialized) {
      return;
    }

    self::$stdOutAppender = function ($debug) {
      echo $debug;
    };

    self::$errorLogAppender = function ($debug) {
      error_log($debug);
    };
  }

  private $_debug;
  private $_appender;

  public function __construct($debug = true) {
    self::_initAppenders();
    $this->_debug = $debug;
    $this->_appender = self::$stdOutAppender;
  }

  public function debug() {
    if (!$this->_debug) {
      return;
    }

    $args = func_get_args();
    $args[0] = rtrim($args[0]);

    $out = call_user_func_array(array('reed\String', 'format'), $args) . "\n";

    $appender = $this->_appender;
    $appender($out);
  }

  public function setAppender($appender) {
    $this->_appender = $appender;
  }

}
