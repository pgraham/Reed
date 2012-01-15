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

/**
 * This class encapsulates an image and allows it to be manipulated.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Image {

  private $_path;
  private $_image;
  private $_type;

  public function __construct($path) {
    $imgInfo = getimagesize($path);
    if ($imgInfo === false) {
      throw new Exception("$path is not a valid image.");
    }

    $this->_type = $imgInfo[2];
    switch ($this->_type) {
      case IMAGETYPE_JPEG:
      $this->_image = imagecreatefromjpeg($path);
      break; 

      case IMAGETYPE_GIF:
      $this->_image = imagecreatefromgif($path);
      break;

      case IMAGETYPE_PNG:
      $this->_image = imagecreatefrompng($path);
      break;

      default:
      throw new Exception("$path is not a supported type: $this->_type");
    }

    $this->_path = $path;
  }

  public function getHeight() {
    return imagesy($this->_image);
  }

  public function getWidth() {
    return imagesx($this->_image);
  }

  public function resizeToHeight($height) {
    $ratio = $height / $this->getHeight();
    $width = $this->getWidth() * $ratio;
    $this->resize($width, $height);
  }

  public function resizeToWidth($width) {
    $ratio = $width / $this->getWidth();
    $height = $this->getHeight() * $ratio;
    $this->resize($width, $height);
  }

  public function resize($width, $height) {
    $resized = imagecreatetruecolor($width, $height);
    imagecopyresampled($resized, $this->_image, 0, 0, 0, 0, $width, $height,
      $this->getWidth(), $this->getHeight());
    $this->_image = $resized;
  }

  public function save($path = null, $type = null, $compression = 75,
      $permissions = null)
  {
    if ($path === null) {
      $path = $this->_path;
    }
    if ($type === null) {
      $type = $this->_type;
    }

    switch ($type) {
      case IMAGETYPE_JPEG:
      imagejpeg($this->_image, $path, $compression);
      break;

      case IMAGETYPE_GIF:
      imagegif($this->_image, $path);
      break;

      case IMAGETYPE_PNG:
      imagepng($this->_image, $path);
      break;

      default:
      throw new Exception("Unsupported type: $type");
    }

    if ($permissions !== null) {
      chmod($path, $permissions);
    }
  }
}
