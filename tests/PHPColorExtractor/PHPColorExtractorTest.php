<?php

declare(strict_types=1);

namespace PHPColorExtractor\Tests;

use Exception;
use PHPColorExtractor\PHPColorExtractor;
use PHPUnit\Framework\TestCase;

class PHPColorExtractorTest extends TestCase
{
    protected function setUp(): void
    {
        // Setup code can go here if needed
    }

    public function testSetImageError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unable to find provided image');

        $object = new PHPColorExtractor();
        $object->setImage('./tests/pdfs/missing.pdf');
    }

    public function testSetTotalColorsErrorWithInvalidType(): void
    {
        $this->expectException(\TypeError::class);

        $object = new PHPColorExtractor();
        /** @phpstan-ignore-next-line */
        $object->setTotalColors('1s1s');
    }

    public function testSetTotalColorsErrorWithNegativeValue(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid total Colors');

        $object = new PHPColorExtractor();
        $object->setTotalColors(-5);
    }

    public function testSetGranularityErrorWithInvalidType(): void
    {
        $this->expectException(\TypeError::class);

        $object = new PHPColorExtractor();
        /** @phpstan-ignore-next-line */
        $object->setGranularity('1s1s');
    }

    public function testSetGranularityErrorWithNegativeValue(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid granularity');

        $object = new PHPColorExtractor();
        $object->setGranularity(-5);
    }

    public function testExtractPaletteError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('An image must be set before its palette can be extracted');

        $object = new PHPColorExtractor();
        $object->extractPalette();
    }

    public function testSetImage(): void
    {
        $object = new PHPColorExtractor();
        $result = $object->setImage('./tests/image/lenna.png');

        $this->assertInstanceOf(PHPColorExtractor::class, $result);
    }

    public function testSetTotalColors(): void
    {
        $object = new PHPColorExtractor();
        $result = $object->setTotalColors(10);

        $this->assertInstanceOf(PHPColorExtractor::class, $result);
    }

    public function testSetGranularity(): void
    {
        $object = new PHPColorExtractor();
        $result = $object->setGranularity(20);

        $this->assertInstanceOf(PHPColorExtractor::class, $result);
    }

    public function testExtractPalette(): void
    {
        $object = new PHPColorExtractor();
        $object->setImage('./tests/image/lenna.png');
        $palette = $object->extractPalette();

        $this->assertCount(10, $palette);
        $this->assertEquals('CC6666', $palette[0]);
    }

    public function testExtractPaletteWithCustomColorCount(): void
    {
        $object = new PHPColorExtractor();
        $object->setImage('./tests/image/lenna.png');
        $object->setTotalColors(5);
        $palette = $object->extractPalette();

        $this->assertCount(5, $palette);
    }

    public function testFluentInterface(): void
    {
        $object = new PHPColorExtractor();
        $palette = $object
            ->setImage('./tests/image/lenna.png')
            ->setTotalColors(15)
            ->setGranularity(10)
            ->extractPalette();

        $this->assertCount(15, $palette);
    }

    public function testSetImageFromStringMatchesSetImage(): void
    {
        $data = file_get_contents('./tests/image/lenna.png');
        $this->assertIsString($data);

        $fromPath = (new PHPColorExtractor())->setImage('./tests/image/lenna.png')->extractPalette();
        $fromString = (new PHPColorExtractor())->setImageFromString($data)->extractPalette();

        $this->assertSame($fromPath, $fromString);
    }

    public function testSetImageFromStringErrorWithInvalidData(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unable to read provided image data');

        (new PHPColorExtractor())->setImageFromString('not an image');
    }

    public function testSetImageFromStringErrorWithEmptyData(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unable to read provided image data');

        (new PHPColorExtractor())->setImageFromString('');
    }

    public function testSetImageFromGd(): void
    {
        $img = imagecreatetruecolor(20, 10);
        $this->assertNotFalse($img);
        $red = imagecolorallocate($img, 255, 0, 0);
        $blue = imagecolorallocate($img, 0, 0, 255);
        $this->assertNotFalse($red);
        $this->assertNotFalse($blue);
        imagefilledrectangle($img, 0, 0, 14, 9, $red);
        imagefilledrectangle($img, 15, 0, 19, 9, $blue);

        $palette = (new PHPColorExtractor())
            ->setImageFromGd($img)
            ->setGranularity(1)
            ->extractPalette();

        $this->assertSame(['FF0000', '0000FF'], $palette);
    }

    public function testLatestImageSourceWins(): void
    {
        $img = imagecreatetruecolor(4, 4);
        $this->assertNotFalse($img);

        $palette = (new PHPColorExtractor())
            ->setImageFromGd($img)
            ->setImage('./tests/image/lenna.png')
            ->extractPalette();

        $this->assertSame('CC6666', $palette[0]);

        $palette = (new PHPColorExtractor())
            ->setImage('./tests/image/lenna.png')
            ->setImageFromGd($img)
            ->extractPalette();

        $this->assertSame(['000000'], $palette);
    }
}
