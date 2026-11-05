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
        // Arrange
        $object = new PHPColorExtractor();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unable to find provided image');

        // Act
        $object->setImage('./tests/pdfs/missing.pdf');
    }

    public function testSetTotalColorsErrorWithInvalidType(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Assert
        $this->expectException(\TypeError::class);

        // Act
        /** @phpstan-ignore-next-line */
        $object->setTotalColors('1s1s');
    }

    public function testSetTotalColorsErrorWithNegativeValue(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid total Colors');

        // Act
        $object->setTotalColors(-5);
    }

    public function testSetGranularityErrorWithInvalidType(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Assert
        $this->expectException(\TypeError::class);

        // Act
        /** @phpstan-ignore-next-line */
        $object->setGranularity('1s1s');
    }

    public function testSetGranularityErrorWithNegativeValue(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid granularity');

        // Act
        $object->setGranularity(-5);
    }

    public function testExtractPaletteError(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('An image must be set before its palette can be extracted');

        // Act
        $object->extractPalette();
    }

    public function testSetImage(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Act
        $result = $object->setImage('./tests/image/lenna.png');

        // Assert
        $this->assertInstanceOf(PHPColorExtractor::class, $result);
    }

    public function testSetTotalColors(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Act
        $result = $object->setTotalColors(10);

        // Assert
        $this->assertInstanceOf(PHPColorExtractor::class, $result);
    }

    public function testSetGranularity(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Act
        $result = $object->setGranularity(20);

        // Assert
        $this->assertInstanceOf(PHPColorExtractor::class, $result);
    }

    public function testExtractPalette(): void
    {
        // Arrange
        $object = new PHPColorExtractor();
        $object->setImage('./tests/image/lenna.png');

        // Act
        $palette = $object->extractPalette();

        // Assert
        $this->assertCount(10, $palette);
        $this->assertEquals('CC6666', $palette[0]);
    }

    public function testExtractPaletteWithCustomColorCount(): void
    {
        // Arrange
        $object = new PHPColorExtractor();
        $object->setImage('./tests/image/lenna.png');
        $object->setTotalColors(5);

        // Act
        $palette = $object->extractPalette();

        // Assert
        $this->assertCount(5, $palette);
    }

    public function testFluentInterface(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Act
        $palette = $object
            ->setImage('./tests/image/lenna.png')
            ->setTotalColors(15)
            ->setGranularity(10)
            ->extractPalette();

        // Assert
        $this->assertCount(15, $palette);
    }

    public function testSetImageFromStringMatchesSetImage(): void
    {
        // Arrange
        $data = file_get_contents('./tests/image/lenna.png');
        $this->assertIsString($data);

        // Act
        $fromPath = (new PHPColorExtractor())->setImage('./tests/image/lenna.png')->extractPalette();
        $fromString = (new PHPColorExtractor())->setImageFromString($data)->extractPalette();

        // Assert
        $this->assertSame($fromPath, $fromString);
    }

    public function testSetImageFromStringErrorWithInvalidData(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unable to read provided image data');

        // Act
        $object->setImageFromString('not an image');
    }

    public function testSetImageFromStringErrorWithEmptyData(): void
    {
        // Arrange
        $object = new PHPColorExtractor();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unable to read provided image data');

        // Act
        $object->setImageFromString('');
    }

    public function testSetImageFromGd(): void
    {
        // Arrange
        $img = imagecreatetruecolor(20, 10);
        $this->assertNotFalse($img);
        $red = imagecolorallocate($img, 255, 0, 0);
        $blue = imagecolorallocate($img, 0, 0, 255);
        $this->assertNotFalse($red);
        $this->assertNotFalse($blue);
        imagefilledrectangle($img, 0, 0, 14, 9, $red);
        imagefilledrectangle($img, 15, 0, 19, 9, $blue);

        // Act
        $palette = (new PHPColorExtractor())
            ->setImageFromGd($img)
            ->setGranularity(1)
            ->extractPalette();

        // Assert
        $this->assertSame(['FF0000', '0000FF'], $palette);
    }

    public function testSetImageAfterSetImageFromGdUsesPath(): void
    {
        // Arrange
        $img = imagecreatetruecolor(4, 4);
        $this->assertNotFalse($img);
        $object = (new PHPColorExtractor())->setImageFromGd($img);

        // Act
        $palette = $object->setImage('./tests/image/lenna.png')->extractPalette();

        // Assert
        $this->assertSame('CC6666', $palette[0]);
    }

    public function testSetImageFromGdAfterSetImageUsesGdImage(): void
    {
        // Arrange
        $img = imagecreatetruecolor(4, 4);
        $this->assertNotFalse($img);
        $object = (new PHPColorExtractor())->setImage('./tests/image/lenna.png');

        // Act
        $palette = $object->setImageFromGd($img)->extractPalette();

        // Assert
        $this->assertSame(['000000'], $palette);
    }
}
