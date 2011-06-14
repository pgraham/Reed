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
 * @package reed/test/generator
 */
namespace reed\test\generator;

use \reed\generator\CodeTemplate;
use \reed\generator\CodeTemplateParser;
use \reed\generator\EachParser;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/../test-common.php';

/**
 * This class tests the CodeTemplateLoader class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package reed/test/generator
 */
class EachParserTest extends TestCase {

  private $_parser;

  protected function setUp() {
    $this->_parser = new EachParser(new CodeTemplateParser());
  }

  protected function tearDown() {
    $this->_parser = null;
  }

  public function testParseEach() {
    $code = file_get_contents(__DIR__ . '/each.template');
    $template = new CodeTemplate();

    $parsed = $this->_parser->parse($code, $template);
    $this->assertNotEquals(false, strpos($parsed, '  ${each1}'), $parsed);
  }

  public function testLoadEach() {
    $code = file_get_contents(__DIR__ . '/each.template');
    $template = new CodeTemplate();

    $parsed = $this->_parser->parse($code, $template);
    $template->setCode($parsed);
    $resolved = $template->forValues(Array
      (
        'eached' => Array('I am line #1', 'I am line #2', 'I am line #3')
      )
    );

    $expected = "This is a sample template that contains an each substitution."
      . "\n\n"
      . "  I am line #1\n"
      . "  I am line #2\n"
      . "  I am line #3\n";

    $this->assertEquals($expected, $resolved);
  }

  public function testResolveEachWithIndexedSubstitutions() {
    $template = new CodeTemplate();
    $code = "This is a sample template that contains an each substitution."
      . "\n\n"
      . "  \${each:indexed AS indexable}\n"
      . "    \${indexable[id]}: \${indexable[val]}\n"
      . "  \${done}\n";

    $parsed = $this->_parser->parse($code, $template);
    $template->setCode($parsed);
    $resolved = $template->forValues(array
      (
        'indexed' => array
          (
            array( 'id' => 1, 'val' => 'I am line #1'),
            array( 'id' => 2, 'val' => 'I am line #2'),
            array( 'id' => 3, 'val' => 'I am line #3')
          )
      )
    );

    $expected = "This is a sample template that contains an each substitution."
      . "\n\n"
      . "  1: I am line #1\n"
      . "  2: I am line #2\n"
      . "  3: I am line #3\n";

    $this->assertEquals($expected, $resolved);
  }
}
