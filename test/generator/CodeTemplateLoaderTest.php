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

use \reed\generator\CodeTemplateLoader;

use \PHPUnit_Framework_TestCase as TestCase;

require_once __DIR__ . '/../test-common.php';

/**
 * This class tests the CodeTemplateLoader class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 * @package reed/test/generator
 */
class CodeTemplateLoaderTest extends TestCase {

  private $_loader;

  protected function setUp() {
    $this->_loader = new CodeTemplateLoader(__DIR__);
  }

  protected function tearDown() {
    $this->_loader = null;
  }

  public function testLoadBasic() {
    $template  = $this->_loader->load('simple', Array
      (
        'sub1' => 'val1',
        'sub2' => 'val2',
        'sub3' => 'val3'
      )
    );

    $expected = "This is a sample template with simple substitutions.\n\n"
      . "This line contains an inline substitution: val1\n"
      . "This line contains two substitutions: val2 val3\n"
      . "The following line contains a value on its own\n"
      . "val1\n";
      
    $this->assertEquals($expected, $template);
  }

  public function testLoadJoin() {
    $template = $this->_loader->load('join', Array
      (
        'joined' => Array('val1', 'val2', 'val3')
      )
    );

    $expected = "This is a sample template that contains a join substitution.\n"
      . "This line contains a join with ',' as the glue: val1,val2,val3\n"
      . "This line contains a join with ' ' as the glue: val1 val2 val3\n";

    $this->assertEquals($expected, $template);
  }

  public function testLoadEach() {
    $template = $this->_loader->load('each', Array
      (
        'eached' => Array('I am line #1', 'I am line #2', 'I am line #3')
      )
    );

    $expected = "This is a sample template that contains an each substitution."
      . "\n\n"
      . "  I am line #1\n"
      . "  I am line #2\n"
      . "  I am line #3\n";

    $this->assertEquals($expected, $template);
  }

  public function testLoadBooleanIf() {
    $template = $this->_loader->load('boolean_if', Array
      (
        'boolval' => true
      )
    );

    $expected = "This is a sample template that contains a simple if"
      . " substitution.\n"
      . "If a value named boolval is present and equal to true then there\n"
      . "will be a message beneath this explanation.";

    $condMsg = "\n\n  I was included conditionally!";

    $this->assertEquals($expected.$condMsg, trim($template));

    $template = $this->_loader->load('boolean_if', Array
      (
        'boolval' => false
      )
    );

    $this->assertEquals($expected, trim($template));
  }

  public function testLoadIf() {
    $template = $this->_loader->load('if', Array
      (
        'value' => 'goodone'
      )
    );

    $expected = "This is a sample template that contains a simple if"
      . " substitution.\n"
      . "If a value named value is present and equal to 'goodone' then there\n"
      . "will be a message beneath this explanation.";
    $condMsg = "\n\n  I was included conditionally!";

    $this->assertEquals($expected.$condMsg, trim($template));

    $template = $this->_loader->load('if', Array
      (
        'value' => 'badone'
      )
    );

    $this->assertEquals($expected, trim($template));
  }

  public function testLoadIfElse() {
    $values = Array();

    $expected = "This is a sample template that contains a simple if else"
      . " substitution.\n"
      . "If a value named value is present and equal to true then there\n"
      . "will be a fun message. Otherwise there will be a serious message."
      . "\n\n";

    $ifMsg = '  OMG if statements fo lyfe!';
    $elseMsg = '  This is the else condition';

    $values['goodone'] = true;
    $template = $this->_loader->load('if_else', $values);
    $this->assertEquals($expected . $ifMsg, trim($template));

    $values['goodone'] = 'giberrish';
    $template = $this->_loader->load('if_else', $values);
    $this->assertEquals($expected . $elseMsg, trim($template));
  }

  public function testLoadIfElseIfElse() {
    $values = Array();

    $expected = "This is a sample template that contains a simple if, elseif,"
      . " else substitution.\n"
      . "If a value named goodone is present and equal to true then there\n"
      . "will be a fun message. If the value is equal to 'goodone' then there\n"
      . "will be a weird message. Otherwise there will be a serious message."
      . "\n\n";

    $ifMsg = '  OMG if statements fo lyfe!';
    $elseifMsg = '  OY, where my BBQ at VANIER WHAAAAAAAAAAT!!!!!!!!!!';
    $elseMsg = '  This is the else condition';

    $values['goodone'] = true;
    $template = $this->_loader->load('if_elseif_else', $values);
    $this->assertEquals($expected . $ifMsg, trim($template));

    $values['goodone'] = 'goodone';
    $template = $this->_loader->load('if_elseif_else', $values);
    $this->assertEquals($expected . $elseifMsg, trim($template));

    $values['goodone'] = 'giberrish';
    $template = $this->_loader->load('if_elseif_else', $values);
    $this->assertEquals($expected . $elseMsg, trim($template));
  }
}
