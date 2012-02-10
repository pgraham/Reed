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

use \SimpleXMLElement;
use \SplFileInfo;

/**
 * This class encapsulates information about a website's directory structure.
 * The class provides paths for the following organizational components.
 *
 * <ul>
 *   <li>root          - The root directory of all website source files.  All
 *       other paths will be a subdirectory of this path.
 *   <li>documentRoot  - The root directory of all web accessible files.
 *   <li>libraries     - The directory where all 3rd party code is kept.
 *   <li>source        - The directory where all non-web accessible source code
 *       is kept.
 * </ul>
 *
 * This class also encapsulates two target paths for generated files.  One web
 * accessible path and one non-web accessible path.  Both these paths should be
 * writable by the web server.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class WebSitePathInfo {

  /**
   * Parse website path info from a given SimpleXMLElement.
   *
   * @param SimpleXMLElement $xmlCfg
   * @param string $pathRoot Root path for any relative paths
   * @return WebSitePathInfo
   */
  public static function parse(SimpleXMLElement $xmlCfg, $pathRoot) {
    $sourceNs = null;
    if (isset($xmlCfg->sourcePath)) {
      $sourceRoot = $xmlCfg->sourcePath->__toString();

      if (substr($sourceRoot, 0, 1) !== '/') {
        $target = $pathRoot . '/' . $sourceRoot;
      }

      if (isset($xmlCfg->sourcePath['nsbase'])) {
        $sourceNs = $xmlCfg->sourcePath['nsbase'];
      }
    } else {
      $sourceRoot = $pathRoot . '/src';
    }


    // Set the output directory, if not specified use a temporary directory
    if (isset($xmlCfg->targetPath)) {
      $target = $xmlCfg->targetPath->__toString();

      if (substr($target, 0, 1) != '/') {
        $target = $pathRoot . '/' . $target;
      }
    } else {
      $target = sys_get_temp_dir() . '/conductor';
    }

    // Parse the document root
    if (isset($xmlCfg->documentRoot)) {
      $docRoot = $xmlCfg->documentRoot->__toString();

      if (substr($docRoot, 0, 1) != '/') {
        $docRoot = $pathRoot . '/' . $docRoot;
      }
    } else {
      $docRoot = $pathRoot . '/public_html';
    }

    // Parse the file system path to the web-accessible folder that is writable
    // by the web server
    if (isset($xmlCfg->webWritable)) {
      $webWrite = $xmlCfg->webWritable->__toString();

      if (substr($webWrite, 0, 1) != '/') {
        $webWrite = $pathRoot . '/' . $webWrite;
      }
    } else {
      $webWrite = $docRoot . '/gen';
    }

    // Parse the root of the website relative to the domain on which it is
    // hosted
    if (isset($xmlCfg->webRoot)) {
      $webRoot = $xmlCfg->webRoot->__toString();
    } else {
      $webRoot = '/';
    }

    $pathInfo = new WebSitePathInfo($pathRoot, $webRoot, $docRoot, null,
      $sourceRoot, $target, $webWrite);
    if ($sourceNs !== null) {
      $pathInfo->setSrcNs($sourceNs);
    }

    return $pathInfo;
  }

  /*
   * ===========================================================================
   * Instance
   * ===========================================================================
   */

  private $_root;
  private $_webRoot;

  private $_doc;
  private $_lib;
  private $_src;
  private $_srcNs;

  private $_target;
  private $_webTarget;

  private $_pathConverter;

  /**
   * Create a new path information object.
   *
   * @param string $root
   *   Path to the root directory of the website.  This is
   *   not necessarily the root path for web-accessible files.  If a relative
   *   path is given it is treated as relative to the current working directory.
   * @param string $webRoot {@default '/'}
   *   Web accessible path to the document root. This is useful for sites that
   *   reside in a user's public_html directory.  In this case the web root
   *   would be defined as '/~user'
   * @param string $docRoot {@default 'public_html'}
   *   Path to the root directory for web-accessible files.
   *   This must be the root directory or a sub directory of the specified root
   *   path. If specified as a relative path it will be interpreted as relative
   *   to the web site's root path.
   * @param string $libRoot {@default 'lib'}
   *   Path to the directory where 3rd party files are kept.  If specified as a
   *   relative path it will be interpreted as relative to the web site's root
   *   path.
   * @param string $srcRoot {@default 'src'}
   *   Path to the directory where non web-accessible source files are kept. If
   *   the specified document root is the same as the web site root, then this
   *   parameter is ignored.  If specified as a relative path it will be
   *   interpreted as relative to the web site's root path.
   * @param string $target {@default 'src/gen'}
   *   Path to the directory where non web-accessible generated files should be
   *   output.  If the specified document root is the same as the web site root,
   *   then this parameter is ignored.  If specified as a relative path it will
   *   be interpreted as relative to the web site's root path.
   * @param string $webTarget {@default 'public_html/gen'}
   *   Path to the directory where web-accessible generated files should be
   *   output.  If the specified as a relative path it will be interpreted as
   *   relative to the web site's root path.
   */
  public function __construct($root, $webRoot = null,
      $docRoot   = null,
      $libRoot   = null,
      $srcRoot   = null,
      $target    = null,
      $webTarget = null)
  {
    $rootInfo = new SplFileInfo($root);
    $this->_root = $rootInfo->getRealPath();

    if ($webRoot === null) {
      $this->_webRoot = '/';
    } else {
      $this->_webRoot = $webRoot;
    }

    // Resolve relative paths and store
    $this->_doc = $this->_getPath($docRoot, 'htdocs');
    $this->_lib = $this->_getPath($libRoot, 'lib');
    $this->_src = $this->_getPath($srcRoot, 'src');
    $this->_target = $this->_getPath($target, $this->_src . '/gen');
    $this->_webTarget = $this->_getPath($webTarget, $this->_doc . '/gen');

    // Instantiate a path converter for transforming file system paths to web
    // paths and vice-versa
    $this->_pathConverter = new FsToWebPathConverter(
      $this->_doc, $this->_webRoot);
  }

  /**
   * Convert the given file system path to a web accessible path.
   *
   * @param string $fsPath
   * @return string
   */
  public function fsToWeb($fsPath) {
    return $this->_pathConverter->fsToWeb($fsPath);
  }

  /**
   * Getter for the web site's document root.
   *
   * @return string
   */
  public function getDocumentRoot() {
    return $this->_doc;
  }

  /** 
   * Getter for the web site's library path.
   *
   * @return string
   */
  public function getLibPath() {
    return $this->_lib;
  }

  /**
   * Getter for the web site's root path.
   *
   * @return string
   */
  public function getRootPath() {
    return $this->_root;
  }

  /**
   * Getter for the web site's source path.
   *
   * @return string
   */
  public function getSrcPath() {
    return $this->_src;
  }

  /**
   * Getter for the namespace of all classes found in the source path.
   *
   * @return string
   */
  public function getSrcNs() {
    return $this->_srcNs;
  }

  /**
   * Getter for the web site's non-web accessible target directory.
   *
   * @return string
   */
  public function getTarget() {
    return $this->_target;
  }

  /**
   * Getter for the web site's web accessible root.
   *
   * @return string
   */
  public function getWebRoot() {
    return $this->_webRoot;
  }

  /**
   * Getter for the web site's web accessible target directory as a file
   * system path.
   *
   * @return string
   */
  public function getWebTarget() {
    return $this->_webTarget;
  }

  /**
   * Setter for the namespace of any classes found in the source path.
   *
   * @param string $srcNs
   */
  public function setSrcNs($srcNs) {
    $this->_srcNs = $srcNs;
  }

  /**
   * Prepends and returns the given path with the web root.
   *
   * @param string $path Web path relative to the web root.
   * @return string
   */
  public function webPath($path) {
    $path = ltrim($path, '/');

    if ($this->_webRoot === '/') {
      return "/$path";
    }

    return "$this->_webRoot/$path";
  }

  /* Resolve the given path relative to the set root path */
  private function _getPath($path, $default) {
    $resolved = $path;
    if ($resolved === null) {
      $resolved = $default;
    }

    if (substr($resolved, 0, 1) === '/') {
      // This is an absolute path
      if (strpos($resolved, $this->_root) === false) {
        return false;
      }
    } else {
      // This is a relative path
      $resolved = $this->_root . '/' . $resolved;
    }

    return $resolved;
  }
}
