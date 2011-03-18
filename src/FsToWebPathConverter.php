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
 * @package reed
 */
namespace reed;

/**
 * This class provides capabilities for determining the web accessible path to
 * a file system path and vice-versa.
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package reed
 */
class FsToWebPathConverter {

  /* The web site's document root */
  private $_docRoot;

  /* The web site's web root */
  private $_webRoot;

  /**
   * Create a new converter for the given document root and the given web root.
   * The web root only needs to be given if it is something other than '/', i.e.
   * /~user.  Trailing slashes are not necessary and will be stripped.
   *
   * @param string $documentRoot The file system path to the root directory of
   *   the website
   * @param string $webRoot The web accessible path to the root directory of the
   *   website.  This is any path needed _AFTER_ the domain.
   */
  public function __construct($documentRoot, $webRoot = '/') {
    $this->_docRoot = $this->_stripTrailingSeparator($documentRoot);

    if ($webRoot !== '/') {
      $this->_webRoot = $this->_stripTrailingSeparator($webRoot);
    }
  }

  /**
   * Convert the given file system path to a web path.
   *
   * @param string $fsPath
   * @return string The web accessible path to the given file system path.
   */
  public function fsToWeb($fsPath) {
    if (strpos($fsPath, $this->_docRoot) === false) {
      throw new Exception('The given file system path is not a subdirectory of'
        . ' the converter\'s document root');
    }

    $webPath = str_replace($this->_docRoot, '', $fsPath);
    if ($this->_webRoot !== '/') {
      $webPath = $this->_webRoot . $webPath;
    }
    return $webPath;
  }

  /* Remove any trailing slashes from the given path. */
  private function _stripTrailingSeparator($path) {
    if (substr($path, -1) === '/') {
      return substr($path, 0, -1);
    }
    return $path;
  }
}
