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
 */
namespace reed\test\generator;

use \PHPUnit_Framework_TestCase as TestCase;

use \reed\generator\CodeTemplateParser;

require_once __DIR__ . '/../test-common.php';

/**
 * This class tests proper template parsing and substitution for if templates.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class IfSubstitutionTest extends TestCase {

  public function testBooleanIf() {
    $expectedBase = file_get_contents(__DIR__ . '/boolean_if-base.expected');
    $templateCode = file_get_contents(__DIR__ . '/boolean_if.template');
    $template = CodeTemplateParser::parse($templateCode);

    $resolved = $template->forValues(array('boolval' => true));
    $condMsg = "\n  I was included conditionally!";
    $this->assertEquals($expectedBase.$condMsg, trim($resolved));

    $resolved = $template->forValues(array('boolval' => false));
    $this->assertEquals(trim($expectedBase), trim($resolved));
  }

  public function testNestedEachIf() {
    $expected = file_get_contents(__DIR__ . '/nested_each_if.expected');
    $templateCode = file_get_contents(__DIR__ . '/nested_each_if.template');
    $template = CodeTemplateParser::parse($templateCode);

    $resolved = $template->forValues(array
      (
        'props' => array(
          array(
            'id'    => 'firstProp',
            'cond1' => true
          ),
          array(
            'id'    => 'twoProp',
            'cond2' => true
          ),
          array(
            'id'    => 'threeProp'
          )
        )
      )
    );

    $this->assertEquals($expected, $resolved);
  }
}
