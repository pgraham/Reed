<?php
/**
 * This class defines global namespace functions for creating primitive wrappers
 * without having to use the new keyword.  Allowing you to chain the
 * instantiation of the primitive wrapper with the invocation of the desired
 * function.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */

function String($str) {
  return new zpt\util\String($str);
}
