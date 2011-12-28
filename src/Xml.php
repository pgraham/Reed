<?php
/**
 * =============================================================================
 * Copyright (c) 2011, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under
 * the 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace reed;

use \DOMCdataSection;
use \DOMDocument;
use \DOMNode;
use \DOMXPath;

/**
 * This class provides various xml utility functions.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Xml {

  /** Regular expression for parsing patch definitions. */
  const PATCH_DEF_RE = '/([\w.]+)\s*=\s*(.*)/';
  const CDATA_RE = '/<\!\[CDATA\[(.+)\]\]>/';

  /**
   * Ensures that a node at the given xpath exists in the given document.  If
   * the node doesn't exist it will be added, along with any necessary parent
   * elements.  The given xpath query should not contain any predicates or
   * wildcard elements.  If a node is added, it will be appended to the end of
   * the first node along the path that exists.  The given
   *
   * @param DOMDocument $dom
   * @param string $query
   */
  public static function ensureNodeExists(DOMDocument $dom, $query) {
    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query($query);
    if ($nodes->length === 0) {
      $pathParts = explode('/', $query);
      $toAdd = array();
      $parentExists = false;

      while (!$parentExists) {
        $toAdd[] = array_pop($pathParts);
        $parent = $xpath->query(implode('/', $pathParts));
        if ($parent->length > 0) {
          $parentExists = true;
        }
      }
      array_reverse($toAdd);

      $parent = $parent->item(0);
      foreach ($toAdd AS $nodeName) {
        $newNode = $dom->createElement($nodeName);
        $parent->appendChild($newNode);
        $parent = $newNode;
      }
    }
  }

  /**
   * This function loads an XML document from a specified path or XML document
   * string and returns it as a DomDocument.  The given $src parameter is first
   * checked as a file path.  If a file exists with the given path then the
   * DomDocument is loaded from that path, otherwise the given string is treated
   * as an XML string.
   *
   * @param mixed $src
   * @return DomDocument
   */
  public static function load($src) {
    $dom = new DOMDocument();

    if (file_exists($src)) {
      $dom->load($src);
    } else {
      $dom->loadXML($src);
    }

    return $dom;
  }

  /**
   * This function loads a series of substitutions from a file or string that
   * can be used with Xml::patch(...).  The given $patch parameter is first
   * checked as a file path.  If a file exists then the patch is loaded from
   * the file, otherwise the given string is parsed directly.
   *
   * @param string $patch Either a file
   * @return array
   */
  public static function parseXmlPatch($patch) {
    if (file_exists($patch)) {
      $patch = file_get_contents($patch);
    }

    $defs = explode("\n", $patch);
    $subs = array();
    foreach ($defs AS $def) {
      if (time($def) === '') {
        continue;
      }

      $match = array();
      if (preg_match(self::PATCH_DEF_RE, $def, $match)) {
        $xpath = '/' . str_replace('.', '/', $match[1]);
        $sub = $match[2];
        $type = null;

        // Special case for boolean, add or remove self closing tag
        if (strtolower($sub) === 'false') {
          $type = 'remove_node';
        } else if (strtolower($sub) === 'true') {
          $type = 'add_node';
        } else {
          $type = 'replace_text';
        }

        $subs[] = array(
          'def' => $def,
          'path' => $xpath,
          'type' => $type,
          'sub' => $sub
        );
      }
    }
    return $subs;
  }

  /**
   * This function applies a series of substitutions to an XML document and
   * returns the result.  If a DOMDocument object is provided as the source,
   * it will not be modified and a new DOMDocument object will be returned.
   *
   * @param mixed $src Either a string path to an xml document, a string
   *   representation of an XML document or a DomDocument object.
   * @param mixed $patch Either a path to a properties file, a string of
   *   substitutions delimitted by line feeds or an array of substitutions in
   *   format descript by Xml::parseXmlPatch($patch);
   * @return DomDocument
   */
  public static function patch($src, $patch) {
    if ($src instanceof DOMDocument) {
      // Create a copy of the given DOMDocument to which the patch will be
      // applied
      $dom = new DOMDocument();
      $dom->loadXml($src->saveXml());
    } else {
      $dom = self::load($src);
    }

    if (!is_array($patch)) {
      $patch = self::parseXmlPatch($patch);
    }

    $xpath = new DOMXPath($dom);
    foreach ($patch AS $sub) {
      $nodes = $xpath->query($sub['path']);
      if ($nodes === false) {
        throw new Exception(
          "Unable to apply XML patch. $def translates to an invalid ".
          "XPath: " . $sub['path']); 
      }

      switch ($sub['type']) {

        case 'add_node':
        self::ensureNodeExists($dom, $sub['path']);
        break;

        case 'remove_node':
        if ($nodes->length > 0) {
          foreach ($nodes AS $node) {
            $node->parentNode->removeChild($node);
          }
        }
        break;

        case 'replace_text':
        if (preg_match(self::CDATA_RE, $sub['sub'], $matches)) {
          foreach ($nodes AS $node) {
            self::removeChildren($node);
            $node->appendChild(new DOMCdataSection($matches[1]));
          }
        } else {
          foreach ($nodes AS $node) {
            $node->nodeValue = $sub['sub'];
          }
        }
        break;
      }
    }

    return $dom;
  }

  /**
   * Remove all the children of a given DOMNode element.
   *
   * @param DOMNode $node
   */
  public static function removeChildren(DOMNode $node) {
    while (isset($node->firstChild)) {
      $node->removeChild($node->firstChild);
    }
  }
}
