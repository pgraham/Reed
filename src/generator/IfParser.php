<?php
/**
 * =============================================================================
 * Copyright (c) 2011, Philip Graham
 * All rights reserved.
 *
 * This file is part of Clarinet and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace reed\generator;

/** 
 * This class parses a code template for if blocks.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class IfParser {

  const ELSE_REGEX     = '/^([\t ]*)\$\{else\}$/';

  const ELSEIF_REGEX   = '/^([\t ]*)\$\{elseif:([^\}]+)\}$/';

  const FI_REGEX       = '/^([\t ]*)\$\{fi\}$/';

  const IF_REGEX       = '/^([\t ]*)\$\{if:([^\}]+)\}$/';

  /* The CodeParser with which to parse code blocks */
  private $_parser;

  /**
   * Create a new IfParser
   *
   * @param CodeTemplateParser $parser
   */
  public function __construct(CodeTemplateParser $parser) {
    $this->_parser = $parser;
  }

  /**
   * Parse the given code for if blocks and populate the CodeTemplate with a
   * matching object representation.
   *
   * @param string $code The code template parse.
   * @param CodeTemplate $template The object to populate.
   * @return The code template with if blocks replaced by shorter tags for
   *   substitution.
   */
  public function parse($code, CodeTemplate $template) {
    $lines = explode("\n", $code);
    $parsedLines = Array();

    $curBlock = null;
    $curClause = null;
    $curCode = Array();
    foreach ($lines AS $line) {
      $ifParams = Array();

      if ($curBlock === null) {

        if (preg_match(self::IF_REGEX, $line, $ifParams)) {
          $ifNum = ++$this->_numIfs;
          $indent = $ifParams[1];
          $expression = $ifParams[2];

          $curBlock = new IfBlock($ifNum, $indent);
          $template->addIf($curBlock);

          $curClause = new IfClause($expression);
          $curBlock->setIf($curClause);

          $parsedLines[] = "$indent\${if{$ifNum}}";
        } else {
          $parsedLines[] = $line;
        }

      } else if (preg_match(self::ELSEIF_REGEX, $line, $ifParams)) {
        $code = $this->_parser->parse(implode("\n", $curCode), $template);
        $curClause->setCode($code);

        $curClause = new ElseIfClause($ifParams[2]);
        $curBlock->addElseIf($curClause);
        $curCode = Array();

      } else if (preg_match(self::ELSE_REGEX, $line, $ifParams)) {
        $code = $this->_parser->parse(implode("\n", $curCode), $template);
        $curClause->setCode($code);

        $curClause = new ElseClause();
        $curBlock->setElse($curClause);
        $curCode = Array();

      } else if (preg_match(self::FI_REGEX, $line, $ifParams)) {
        $code = $this->_parser->parse(implode("\n", $curCode), $template);
        $curClause->setCode($code);

        $curBlock = null;
        $curClause = null;
        $curCode = Array();

      } else {
        $curCode[] = $line;
      }
    }

    return implode("\n", $parsedLines);
  }
}
