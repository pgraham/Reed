<?php
namespace Reed;
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
 * This class encapsulates configuration settings that are common to different
 * components of a web framework
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package Reed
 */
class Config {

  private static $_config;

  public static function getWebSiteRoot() {
    return self::_ensureConfig()->webSiteRoot();
  }

  public static function getDocumentRoot() {
    return self::_ensureConfig()->documentRoot();
  }

  public static function getWebWritableDir() {
    return self::_ensureConfig()->webWritableDir();
  }

  public static function getSessionTtl() {
    return self::_ensureConfig()->sessionTtl();
  }

  public static function setConfig(Config $config) {
    self::$_config = $config;
  }

  public static function get() {
    return self::$_config;
  }

  public static function isDebug() {
    return defined('DEBUG') && DEBUG === true;
  }

  private static function _ensureConfig() {
    if (self::$_config === null) {
      self::setConfig(new Config());
    }
    return self::$_config;
  }

  /*
   * =========================================================================
   * Instance
   * =========================================================================
   */

  /**
   * Getter for the file system path to the root of the web site.  Default
   * assumes that the current config instance is in desired directory.
   *
   * A common setup is to keep this file one directory about the web accessible
   * root and to keep web accessible files in a folder called public_html.  In
   * this case this method should be overridden to return:
   *   __DIR__ . '/public_html'
   *
   * This method is provided as a more reliable of detecting the document root
   * than $_SERVER['DOCUMENT_ROOT'] which will not be set when running from the
   * command line, such as during unit tests.
   */
  protected function documentRoot() {
    return __DIR__;
  }

  /**
   * Getter for the site's session TTL in seconds.  This is used by Reed's
   * Auth functionality.
   */
  protected function sessionTtl() {
    return 1209600; // 60 * 60 * 24 * 14 -- 14 days in seconds
  }

  /**
   * Getter for the web path to the root of the site relative to the site's
   * domain.  Default '/'.  This is useful for websites that live in a userdir.
   * E.g. if the site lives in www.example.com/~foo/bar the this method should
   * return '/~foo/bar/'.
   */
  protected function webSiteRoot() {
    return '/';
  }

  /**
   * Getter for the site's web accessible directory that is writeable by the
   * web server.  This is a file system path.
   */
  protected function webWritableDir() {
    return $this->documentRoot().'/gen';
  }
}
