<?php
/*
 * This file is part of the sfImageTransform package.
 * (c) 2007 Stuart <stuart.lowes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * BaseImageTransformOverlay class.
 *
 * Overlays an image on top of another image.
 *
 * Overlays an image at a set point on the image.
 *
 * @package sfImageTransform
 * @subpackage transforms
 * @author Stuart Lowes <stuart.lowes@gmail.com>
 * @version SVN: $Id$
 */
class BaseImageTransformOverlay extends BaseImageTransform
{
  /**
   * The overlay sfImage.
  */
  protected $overlay;

  /**
   * The left coordinate for the overlay position.
  */
  protected $left = 0;

  /**
   * The top coordinate for the overlay position.
  */
  protected $top = 0;

  /**
   * The named position for the overlay
   */
  protected $position = null;

  /**
   * available labels for overlay positions
   */
  protected $labels = array(
                            'top', 'bottom','left' ,'right', 'middle', 'center',
                            'top-left', 'top-right', 'top-center',
                            'middle-left', 'middle-right', 'middle-center',
                            'bottom-left', 'bottom-right', 'bottom-center',
                           );


  /**
   * Construct an sfImageOverlay object.
   *
   * @param array mixed
   */
  public function __construct(sfImage $overlay, $position='top-left')
  {
    $this->setOverlay($overlay);

    if (is_array($position) && count($position))
    {

      $this->setLeft($position[0]);

      if (isset($position[1]))
      {
        $this->setTop($position[1]);
      }
    }

    else
    {
      $this->setPosition($position);
    }
  }

  /**
   * sets the over image.
   *
   * @param sfImage
   */
  function setOverlay(sfImage $overlay)
  {
    $this->overlay = $overlay;
  }

  /**
   * returns the overlay sfImage object.
   *
   * @return sfImage
   */
  function getOverlay()
  {
    return $this->overlay;
  }

  /**
   * Sets the left coordinate
   *
   * @param integer
   */
  public function setLeft($left)
  {
    if(is_numeric($left))
    {
      $this->left = $left;

      return true;
    }

    return false;
  }

  /**
   * returns the left coordinate.
   *
   * @return integer
   */
  public function getLeft()
  {
    return $this->left;
  }

  /**
   * set the top coordinate.
   *
   * @param integer
   */
  public function setTop($top)
  {
    if(is_numeric($top))
    {
      $this->top = $top;

      return true;
    }

    return false;
  }

  /**
   * returns the top coordinate.
   *
   * @return integer
   */
  public function getTop()
  {
    return $this->top;
  }

  /**
   * set the named position
   *
   * @param string $position named position. Possible named positions:
   *                - top (alias of top-center),
   *                - bottom (alias of botom-center),
   *                - left ( alias of top-left),
   *                - right (alias of top-right),
   *                - center (alias of middle-center1),
   *                - top-left, top-right, top-center,
   *                - middle-left, middle-right, middle-center,
   *                - bottom-left, bottom-right, bottom-center
   *
   * @return boolean
   */
  public function setPosition($position)
  {

    if(in_array($position, $this->labels))
    {
      $this->position = strtolower($position);

      return true;
    }

    return false;
  }

  /**
   * returns the position name
   *
   * @return string
   */
  public function getPosition()
  {
    return $this->position;
  }

  /**
   * Computes the offset of the overlayed image and sets
   * the top and left coordinates based on the named position
   *
   * @param sfImage $image canvas image
   *
   * @return boolean
   */
  public function computeCoordinates(sfImage $image)
  {
    $position = $this->getPosition();

    if (is_null($position))
    {
      return false;
    }

    $resource   = $image->getAdapter()->getHolder();
    $resource_x = ImageSX($resource);
    $resource_y = ImageSY($resource);

    $overlay    = $this->getOverlay()->getAdapter()->getHolder();
    $overlay_x  = ImageSX($overlay);
    $overlay_y  = ImageSY($overlay);

    switch (strtolower($position))
    {
      case 'top':
      case 'top-left':
        $this->setLeft(0);
        $this->setTop(0);
        break;
      case 'bottom':
      case 'bottom-left':
        $this->setLeft(0);
        $this->setTop($resource_y-$overlay_y);
        break;
      case 'left':
        $this->setLeft(0);
        $this->setTop(round(($resource_y - $overlay_y)/2));
        break;
      case 'right':
        $this->setLeft(round($resource_x - $overlay_x));
        $this->setTop(round(($resource_y - $overlay_y)/2));
        break;
      case 'top-right':
        $this->setLeft($resource_x - $overlay_x);
        $this->setTop(0);
        break;
      case 'bottom-right':
        $this->setLeft($resource_x - $overlay_x);
        $this->setTop($resource_y - $overlay_y);
        break;
      case 'bottom-center':
        $this->setLeft(round(($resource_x - $overlay_x) / 2));
        $this->setTop(round($resource_y - $overlay_y));
        break;
      case 'center':
      case 'middle-center':
        $this->setLeft(round(($resource_x - $overlay_x) / 2));
        $this->setTop(round(($resource_y - $overlay_y) / 2));
        break;
      case 'middle-left':
        $this->setLeft(0);
        $this->setTop(round(($resource_y - $overlay_y) / 2));
        break;
      case 'middle-right':
        $this->setLeft(round($resource_x - $overlay_x));
        $this->setTop(round(($resource_y - $overlay_y) / 2));
        break;
      case 'bottom-left':
      default:
        $this->setLeft(0);
        $this->setTop($resource_y - $overlay_y);
        break;
    }

    return true;
  }

  /**
   * Apply the transform to the sfImage object.
   *
   * @param integer
   * @return sfImage
   */
  protected function transform(sfImage $image)
  {
    return $image;
  }
}
