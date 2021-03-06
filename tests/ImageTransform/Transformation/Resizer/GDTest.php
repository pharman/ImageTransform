<?php
/**
 * This file is part of the ImageTransform package.
 * (c) Christian Schaefer <caefer@ical.ly>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ImageTransform\Tests\Transformation\Resizer;

use ImageTransform\Image;
use ImageTransform\Transformation\Resizer\GD as Resizer;

class GDTest extends \PHPUnit_Framework_TestCase
{
  protected function setUp()
  {
    $width = 10;
    $height = 10;
    $this->resource = imagecreatetruecolor($width, $height);

    $this->image = $this->getMock('\ImageTransform\Image', array('create', 'open', 'flush', 'save', 'saveAs', 'initialize'));
    $this->image->set('image.resource', $this->resource);
    $this->image->set('image.width', $width);
    $this->image->set('image.height', $height);

    $this->resize = new Resizer();
  }

  /**
   * @covers \ImageTransform\Transformation\Resizer\GD::doResize
   */
  public function testResizing()
  {
    $width = 20;
    $height = 20;

    $this->image = $this->resize->resize($this->image, $width, $height);
    $this->assertEquals($width, $this->image->get('image.width'));
    $this->assertEquals($height, $this->image->get('image.height'));
    $this->assertNotEquals($this->resource, $this->image->get('image.resource'));
    $this->assertInternalType('resource', $this->image->get('image.resource'));
  }
}
