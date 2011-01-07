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
 * This class encapsulates Reed's configuration settings.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @package Reed
 */
class Reed_Config {

    private static $_config;

    public static function getWebSiteRoot() {
        return self::_ensureConfig()->webSiteRoot();
    }

    public static function getFileSystemRoot() {
        return self::_ensureConfig()->fileSystemRoot();
    }

    public static function getWebWritableDir() {
        return self::_ensureConfig()->webWritableDir();
    }

    public static function getSessionTtl() {
        return self::_ensureConfig()->sessionTtl();
    }

    public static function setConfig(Reed_Config $config) {
        self::$_config = $config;
    }

    public static function get() {
        return self::$_config;
    }

    private static function _ensureConfig() {
        if (self::$_config === null) {
            self::setConfig(new Reed_Config());
        }
        return self::$_config;
    }

    /*
     * =========================================================================
     * Instance
     * =========================================================================
     */

    /**
     * Getter for the web path to the root of the site relative to the site's
     * domain.  Default '/'.
     */
    protected function webSiteRoot() {
        return '/';
    }

    /**
     * Getter for the file system path to the root of the web site.  Default
     * assumes that the current config instance is in desired directory.
     */
    protected function fileSystemRoot() {
        $class = new ReflectionClass(self::_ensureConfig());
        return dirname($class->getFileName());
    }

    /**
     * Getter for the site's web accessible directory that is writeable by the
     * web server.  This is a file system path.
     */
    protected function webWritableDir() {
        return $this->fileSystemRoot().'/usr';
    }

    /**
     * Getter for the site's session TTL in seconds.
     */
    protected function sessionTtl() {
        return 1209600; // 60 * 60 * 24 * 14 -- 14 days in seconds
    }
}
