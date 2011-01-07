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
 * This class is an extension to Oboe_Head_Javascript that adds the client side
 * proxy for a Bassoon service to the HEAD element.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @package Reed
 */
class Reed_ServiceProxy {

    private $_elm;

    public function __construct($serviceClass) {
        $srvcDef = new Bassoon_RemoteService($serviceClass);

        $fsRoot = Reed_Config::getFileSystemRoot();
        $fsWrite = Reed_Config::getWebWritableDir();
        $relWrite = str_replace($fsRoot, '', $fsWrite);

        $webRoot = Reed_Config::getWebSiteRoot();
        $srvcDef->setWebPath($webRoot.$relWrite.'/ajx');

        $srvcDef->setProxyDir($fsWrite.'/js');
        $srvcDef->setServiceDir($fsWrite.'/ajx');
        $srvcDef->setCsrfToken('reedsessid');

        $gen = new Bassoon_Generator($srvcDef);
        $gen->generate();

        $proxyWeb = $webRoot.$relWrite.'/js/'.$serviceClass.'.js';
        $this->_elm = new Oboe_Head_Javascript($proxyWeb);
    }

    public function getElement() {
        return $this->_elm;
    }
}
