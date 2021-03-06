<?php
/**
 * This file is part of the ImageTransform package.
 * (c) Christian Schaefer <caefer@ical.ly>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ImageTransform\Tests\Image;

use ImageTransform\Image\GD as Image;

class GDTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @covers \ImageTransform\Image\GD::initialize
   */
  public function testInitialization()
  {
    if (!extension_loaded('gd'))
    {
      $this->setExpectedException('\RuntimeException');
    }

    $image = new Image();
    $this->assertInstanceof('\ImageTransform\Image', $image);
  }

  /**
   * @covers \ImageTransform\Image\GD::create
   * @covers \ImageTransform\FileAccessAdapter::create
   */
  public function testCreation()
  {
    $width = 100;
    $height = 200;

    $image = new Image();
    $image->create($width, $height);

    $this->assertInternalType('resource', $image->get('image.resource'));
    $this->assertEquals($width,  $image->get('image.width'));
    $this->assertEquals($height, $image->get('image.height'));
  }

  /**
   * @dataProvider fixtureImages
   * @covers \ImageTransform\Image\GD::open
   * @covers \ImageTransform\FileAccessAdapter::open
   */
  public function testOpening($filepath, $mimeType, $width, $height)
  {
    $image = new Image($filepath);

    $this->assertInternalType('resource', $image->get('image.resource'));
    $this->assertEquals($filepath, $image->get('image.filepath'));
    $this->assertEquals($width,    $image->get('image.width'));
    $this->assertEquals($height,   $image->get('image.height'));
    $this->assertEquals($mimeType, $image->get('image.mime_type'));
  }

  /**
   * @expectedException \InvalidArgumentException
   * @covers \ImageTransform\Image\GD::open
   * @covers \ImageTransform\FileAccessAdapter::open
   */
  public function testOpeningOfUnreadableFile()
  {
    $filepath = '/does/not/exist';
    $image = new Image($filepath);
  }

  /**
   * @expectedException \UnexpectedValueException
   * @covers \ImageTransform\Image\GD::open
   * @covers \ImageTransform\FileAccessAdapter::open
   */
  public function testOpeningOfUnsupportedMimeType()
  {
    $filepath = __FILE__;
    $image = new Image($filepath);
  }

  /**
   * @covers \ImageTransform\Image\GD::flush
   * @covers \ImageTransform\FileAccessAdapter::flush
   */
  public function testFlushing()
  {
    $image = $this->getMock('\ImageTransform\Image\GD', array('out'));
    $image->expects($this->once())->method('out');

    $image->flush();
  }

  /**
   * @covers \ImageTransform\Image\GD::save
   * @covers \ImageTransform\FileAccessAdapter::save
   */
  public function testSaving()
  {
    $image = $this->getMock('\ImageTransform\Image\GD', array('saveAs'));
    $image->expects($this->once())->method('saveAs');
    $image->set('image.filepath', '/path/to/some/file');

    $image->save();
  }

  /**
   * @expectedException \InvalidArgumentException
   * @covers \ImageTransform\Image\GD::save
   * @covers \ImageTransform\FileAccessAdapter::save
   */
  public function testSavingWithNoFilepath()
  {
    $image = $this->getMock('\ImageTransform\Image\GD', array('saveAs'));
    $image->save();
  }

  /**
   * @covers \ImageTransform\Image\GD::saveAs
   * @covers \ImageTransform\FileAccessAdapter::saveAs
   */
  public function testSavingAtGivenFilepath()
  {
    $image = $this->getMock('\ImageTransform\Image\GD', array('out'));
    $image->expects($this->once())->method('out');

    $image->saveAs(sys_get_temp_dir());
  }

  /**
   * @expectedException \InvalidArgumentException
   * @covers \ImageTransform\Image\GD::saveAs
   * @covers \ImageTransform\FileAccessAdapter::saveAs
   */
  public function testSavingAtGivenUnwritableFilepath()
  {
    $image = $this->getMock('\ImageTransform\Image\GD', array('out'));
    $image->saveAs('/');
  }

  /**
   * @dataProvider fixtureImages
   * @covers \ImageTransform\Image\GD::out
   */
  public function testFlushingDifferentMimeTypes($filepath, $mimeType, $width, $height)
  {
    $image = new Image($filepath);

    ob_start();
    $image->flush();
    $this->assertNotEmpty(ob_get_contents());
    ob_end_clean();
  }

  /**
   * @expectedException \UnexpectedValueException
   * @covers \ImageTransform\Image\GD::out
   */
  public function testFlushingWhenNoResourceIsSet()
  {
    $image = new Image();
    $image->flush();
  }

  /**
   * @expectedException \UnexpectedValueException
   * @covers \ImageTransform\Image\GD::out
   */
  public function testFlushingUnsupportedMimeType()
  {
    $image = new Image();
    $image->set('image.resource', __FILE__);

    $image->flush('mime/unsupported');
  }

  public static function fixtureImages()
  {
    return array(
      array(__DIR__.'/../../fixtures/20x20-pattern.gif', 'image/gif', 20, 20),
      array(__DIR__.'/../../fixtures/20x20-pattern.jpg', 'image/jpeg', 20, 20),
      array(__DIR__.'/../../fixtures/20x20-pattern.png', 'image/png', 20, 20)
    );
  }
}
