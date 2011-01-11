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
 * This class encapsulates a form that displays groups of input elements
 * as a two column table of label's and input elements.  This form has a minimal
 * implementation and is intended for use with AJAX forms.  As a result it lacks
 * some interface you might expect with a form object such as the ability to
 * specify method and action attributes.  Should they be required these
 * attributes can be specified using the setAttribute(name, value)  method.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @package Reed
 */
class Reed_LabelForm extends Oboe_ElementComposite implements Oboe_Item_Body {

    private $_tbl;
    private $_cnt;

    /**
     * Create a new LabelledForm object.
     *
     * @param string
     * @param string
     */
    public function __construct($id = null, $class = null) {
        parent::__construct('form', $id, $class);

        $this->_tbl = new Oboe_Table();
        $this->_elements[] = $this->_tbl;

        $this->_cnt = 0;
    }

    public function addFormItem($lbl, Oboe_Item_Form $input) {
        $this->_tbl->addCell($this->_cnt, $lbl);
        $this->_tbl->addCell($this->_cnt++, new Reed_Form_AjaxInput($input));
    }
}
