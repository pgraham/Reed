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
 * This class extends Oboe_Anchor for anchors that are used to perform a dynamic
 * action and that use an image for their display.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @package Reed
 */
class Reed_DynamicImageAnchor extends Oboe_Anchor {

    /**
     * Constructor.
     *
     * @param string Image src path
     */
    public function __construct($src, $alt = null) {
        if ($alt === null) {
            $alt = 'Dynamic Action';
        }
        parent::__construct('#', new Oboe_Image($src, $alt));
    }
}
