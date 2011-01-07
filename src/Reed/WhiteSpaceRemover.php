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
 * @package Reed
 */
/**
 * This class uses output buffering to remove any unneccessary white space
 * characters from output.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @package Reed
 */
class Reed_WhiteSpaceRemover {

    /* Instance state */
    private $_removing = false;

    /** Constructor */
    public function __construct() {}

    /** Removes extra white space charaters from output */
    public function removeWhiteSpace($output) {
        return preg_replace('/[\n\r\t]/', '', $output);
    }

    /** Start removing white space */
    public function start() {
        if ($this->_removing) {
            return;
        }
        ob_start(array($this, 'removeWhiteSpace'));
        $this->_removing = true;
    }

    /** Stop removing white space */
    public function stop() {
        if ($this->_removing) {
            ob_end_flush();
            $this->_removing = false;
        }
    }
}
