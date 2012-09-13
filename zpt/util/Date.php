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

use \DateInterval;
use \DatePeriod;
use \DateTime;
use \Exception;

/**
 * This class provides various date utility functions.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Date {

  /**
   * Return an array with a key for each day in the given interval.
   *
   * @param mixed $from The start of the date range.  Either a date string or a
   *   DateTime instance.
   * @param mixed $to The end of the date range.  Either a date string or a
   *   DateTime instance.
   * @param mixed $int [Optional] The interval between each date in the
   *   resulting array.  Either a interval spec string or a DateInterval
   *   instance.  Default interval is 1 day.
   * @param mixed $val [Optional] The value to use as the value for each array
   *   entry. Default is `null`.
   * @return array
   */
  public static function dateRangeArray($from, $to, $int = null, $val = null) {
    if (is_string($from)) {
      $from = new DateTime($from);
    }
    if (is_string($to)) {
      $to = new DateTime($to);
    }

    if ($int === null) {
      // Set default interval of 1 day
      $int = 'P1D';
    }

    if (is_string($int)) {
      $int = new DateInterval($int);
    }

    if (!($from instanceof DateTime)) {
      throw new Exception('$from must be a valid date string or a DateTime ' .
        'instance.');
    }

    if (!($to instanceof DateTime)) {
      throw new Exception('$to must be a valid date string or a DateTime ' .
        'instance.');
    }

    if (!($int instanceof DateInterval)) {
      throw new Exception('$int must be a valid interval spec or a ' .
        'DateInterval instance.');
    }

    $days = new DatePeriod($from, new DateInterval('P1D'), $to);
    $range = array();
    foreach ($days as $day) {
      $range[$day->format('Y-m-d H:i:s')] = $val;
    }
    return $range;
  }
}
