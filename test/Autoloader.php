<?php
namespace ReedTest;
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
 * @package Reed_Test
 */

/**
 * Autoloader for reed's test cases.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @packacge Reed_Test
 */
class Autoloader {

  /* This is the base path where the Reed_*Test class files are found. */
  public static $basePath = __DIR__;

  /**
   * Autoload function for Reed_*Test class files.
   *
   * @param string - the name of teh class to load
   */
  public static function loadClass($className) {
    if (substr($className, 0, 9) != 'ReedTest\\') {
      return;
    }

    $logicalPath = str_replace('\\', '/', substr($className, 9));
    $fullPath = self::$basePath.'/'.$logicalPath.'.php';
    if (file_exists($fullPath)) {
      require_once $fullPath;
    }
  }
}
spl_autoload_register(array('ReedTest\Autoloader', 'loadClass'));
