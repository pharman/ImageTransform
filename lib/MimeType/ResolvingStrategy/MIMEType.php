<?php
/*
 * This file is part of the sfImageTransform package.
 * (c) 2007 Stuart Lowes <stuart.lowes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * MimeType_ResolvingStrategy_MIMEType
 *
 * ...
 *
 * @package    sfImageTransform
 * @subpackage MimeType_ResolvingStrategy
 * @author     Christian Schaefer <caefer@ical.ly>
 * @version    SVN: $Id$
 */
class MimeType_ResolvingStrategy_MIMEType implements MimeType_ResolvingStrategy_Interface
{
  /**
   * Resolve and return mime type of given filepath
   * 
   * @param  string $filepath Absolute path to the file of which to detect the mime type
   * @return string           The resolved mime type or boolean false
   */
  public function resolve($filepath)
  {
    if (@include_once 'MIME/Type.php')
    {
      if (method_exists('MIME_Type', 'autoDetect'))
      {
        return @MIME_Type::autoDetect($filename);
      }
    }

    return false;
  }
}