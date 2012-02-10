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
 * This class encapsulates a parsed code template.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class CodeTemplate extends CodeBlock {

  /**
   * This method loads the template at the given path, resolves it with the
   * provided values and saves the resolved template to the given output path.
   *
   * @param string $templatePath
   * @param string $resolvedPath
   * @param array $values
   */
  public static function compile($templatePath, $resolvedPath, array $values) {
    $templateLoader = CodeTemplateLoader::get(dirname($templatePath));
    $compiled = $templateLoader->load(basename($templatePath), $values);
    file_put_contents($resolvedPath, $compiled);
  }

  /*
   * ===========================================================================
   * Instance
   * ===========================================================================
   */

  /**
   * Create a new code template for the given text.
   *
   * @param string $template
   */
  public function __construct() {
    // Initialize the parent with no indent
    parent::__construct('');
  }
}
