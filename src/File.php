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
 * This class provides various file utility functions.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class File {

  /**
   * Join a list paths together.  If the first path in the list is absolute
   * then an absolute path is returned, otherwise a relative path is returned.
   *
   * @param string... Any number of paths to join together.
   * @return string
   */
  public static function joinPaths() {
    $paths = func_get_args();
    if (count($paths) === 0) {
      return '';
    }

    $path = implode(DIRECTORY_SEPARATOR, array_map(function ($path) {
      return trim($path, DIRECTORY_SEPARATOR);
    }, $paths));

    // If the first path in the list is absolute return an absolute path,
    // otherwise return a relative path
    // TODO Make an isAbsolute($path) function that handles window paths
    // TODO Make a makeAbsolute($path, $drive) function that handles windows
    //      paths
    return substr($paths[0], 0, 1) === DIRECTORY_SEPARATOR
      ? DIRECTORY_SEPARATOR . $path
      : $path;
  }

  /**
   * Remove any trailing slashes from the given string.
   *
   * @param string $path
   * @return string
   */
  public static function rtrim($path) {
    return rtrim($path, DIRECTORY_SEPARATOR);
  }
}
