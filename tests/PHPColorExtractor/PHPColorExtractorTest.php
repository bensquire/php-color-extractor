<?php

use PHPColorExtractor\PHPColorExtractor;

class PHPColorExtractorTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    /**
     * @expectedException \Exception
     */
    public function testSetImageError()
    {
        $object = new PHPColorExtractor();
        $object->setImage('./tests/pdfs/missing.pdf');
    }

    /**
     * @expectedException \Exception
     */
    public function testSetTotalColorsError()
    {
        $object = new PHPColorExtractor();
        $object->setTotalColors('1s1s');
    }

    /**
     * @expectedException \Exception
     */
    public function testSetGranularityErro()
    {
        $object = new PHPColorExtractor();
        $object->setGranularity('1s1s');
    }

    /**
     * @expectedException \Exception
     */
    public function testExtractPaletteError()
    {
        $object = new PHPColorExtractor();
        $object->extractPalette(20);
    }

    public function testSetImage()
    {
        $object = new PHPColorExtractor();
        $object->setImage('./tests/image/lenna.png');
    }

    public function testSetTotalColors()
    {
        $object = new PHPColorExtractor();
        $object->setTotalColors(10);
    }

    public function testSetGranularity()
    {
        $object = new PHPColorExtractor();
        $object->setGranularity(20);
    }

    public function testExtractPalette()
    {
        $object = new PHPColorExtractor();
        $object->setImage('./tests/image/lenna.png');
        $palette = $object->extractPalette(20);

        $this->assertEquals('CC6666', $palette[0]);
    }
}