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
 * @package Reed
 */
/**
 * Autoloader for Reed_* classes.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @package Reed
 */
class Reed_Autoloader {

    /* This is the base path where the Reed_* class files are found. */
    public static $basePath;

    /**
     * Autoload function for Reed_* class files.
     *
     * @param string - the name of the class to load
     */
    public static function loadClass($className) {
        $pathComponents = explode('_', $className);

        // Make sure we're in the right package
        $base = array_shift($pathComponents);
        if ($base != 'Reed') {
            return;
        }

        $logicalPath = implode('/', $pathComponents);
        $fullPath = self::$basePath.'/'.$logicalPath.'.php';
        if (file_exists($fullPath)) {
            require_once $fullPath;
        }
    }
}

# Static initializer
Reed_Autoloader::$basePath = dirname(__FILE__);
spl_autoload_register(array('Reed_Autoloader', 'loadClass'));
