<?php
/**
 * =============================================================================
 * Copyright (c) 2013, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under the
 * 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zpt\util\test; 
use \zpt\util\String;
use \zpt\util\StringUtils;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/test-common.php';

/**
 * This class tests the reed\String classes functions.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class StringTest extends TestCase {

  /**
   * Tests the fromCamelCase function with default parameters
   */
  public function testFromCamelCaseDefault() {
    $tests = array(
      'aCamelCasedString' => 'a_camel_cased_string',
      'anotherCamelCasedString' => 'another_camel_cased_string',
      'FirstCharacterCapitalized' => 'first_character_capitalized',
      'containsASingleLetterWord' => 'contains_a_single_letter_word',
      'containsASeriesOfCapitalsABCD' => 'contains_a_series_of_capitals_a_b_c_d'
    );

    foreach ($tests AS $subject => $expected) {
      $actual = StringUtils::fromCamelCase($subject);
      $this->assertEquals($expected, $actual);
    }
  }

  /**
   * Tests the fromCamelCase function with the CAPITALIZE_WORDS flag set.
   */
  public function testFromCamelCaseCapitalizeWords() {
    $tests = array(
      'aCamelCasedString' => 'A Camel Cased String',
      'anotherCamelCasedString' => 'Another Camel Cased String',
      'FirstCharacterCapitalized' => 'First Character Capitalized',
      'containsASingleLetterWord' => 'Contains A Single Letter Word',
      'containsASeriesOfCapitalsABCD' => 'Contains A Series Of Capitals A B C D'
    );

    foreach ($tests AS $subject => $expected) {
      $actual = StringUtils::fromCamelCase($subject, ' ', StringUtils::CAPITALIZE_WORDS);
      $this->assertEquals($expected, $actual);
    }
  }

  /**
   * Tests the toCamelCase function.
   */
  public function testToCamelCaseDefault() {
    $tests = array(
      'a_camel_cased_string' => 'aCamelCasedString',
      'another_camel_cased_string' => 'anotherCamelCasedString',
      'First_character_capitalized' => 'FirstCharacterCapitalized',
      'contains_a_single_letter_word' => 'containsASingleLetterWord',
      'contains_a_series_of_capitals_a_b_c_d' => 'containsASeriesOfCapitalsABCD'
    );

    foreach ($tests AS $subject => $expected) {
      $actual = StringUtils::toCamelCase($subject);
      $this->assertEquals($expected, $actual);
    }
  }

  /**
   * Tests the format function.
   */
  public function testFormat() {
    $actual = StringUtils::format("I have two {0} in my {1}.", 'apples', 'pocket');
    $expected = "I have two apples in my pocket.";

    $this->assertEquals($expected, $actual);
  }
}
