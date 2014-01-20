<?php
/**
 * Copyright (c) 2012, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zpt\util;

use \SplFileInfo;

/**
 * This class provides various file utility functions.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class File {

  const DIRECTORY_LOCK_TIMEOUT = 10; // 10 seconds

  /**
   * Check that the given filename has the specified extension
   *
   * @param string $fname The filename to check.
   * @param string $extension The extension to check for.
   * @return boolean Whether or not the given filename has the specified
   *   extension.
   */
  public static function checkExtension($fname, $extension) {
    return substr($fname, -1 * (strlen($extension) + 1)) === ".$extension";
  }

  /**
   * Aquire a directory lock.  Note that this locking is not enforced by the
   * filesystem and is cooperative.  This means that code that does not honour
   * this locking system can still manipulate a locked directory.
   *
   * The specified directory must be writable by the current process in order
   * to aquire the lock.
   *
   * @param string $dir The path of the directory to lock
   * @param boolean $force Forcefully aquire the lock by removing any existing
   *   lock prior to aquiring
   * @throws DirectoryLockTimeoutException
   */
  public static function dirlock($dir, $force = false) {
    if (!is_writeable($dir)) {
      return false;
    }

    $lockDir = "$dir/.reedlock";

    if ($force && file_exists($lockDir)) {
      rmdir($lockDir);
    }

    $start = time();
    while (!@mkdir($lockDir)) {
      // Check if the wait timeout has been exceeded
      $waited = time() - $start;
      if ($waited > self::DIRECTORY_LOCK_TIMEOUT) {
        throw new DirectoryLockTimeoutException();
      }

      usleep(100000); // Sleep for 100ms
    }

    return true;
  }

  /**
   * Release a directory lock.
   *
   * The specified directory must be writable by the current process in order to
   * release the lock.
   */
  public static function dirunlock($dir) {
    rmdir("$dir/.reedlock");
  }

  /**
   * Check if the given file is hidden using the UNIX convention of prefixing
   * the filename using a '.'.
   *
   * @param string $fname SplFileInfo instance or the name or path of the file
   *   to check
   * @return boolean Whether or not the file is hidden per UNIX convention.
   */
  public static function isHidden($fname) {
    if ($fname instanceof SplFileInfo) {
      $fname = $fname->getFilename();
    } else {
      $fname = basename($fname);
    }

    return substr($fname, 0, 1) === '.';
  }

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
