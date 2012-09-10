<?php
/**
 * Copyright (c) 2012, Philip Graham
 * All rights reserved.
 *
 * This file is part of Conductor and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zpt\util\file;

/**
 * This class lists files according to specified criteria.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
interface FileLister {

  /**
   * This function lists all files in a given directory that match a specified
   * pattern.
   *
   * @param string $dir Path to the directory of which to list files matching
   *   the given pattern.
   * @param string $pattern The pattern of the files to list.
   * @return string[]
   */
  public function list($dir, $pattern);
}
