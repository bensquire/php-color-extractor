<?php

declare(strict_types=1);

namespace PHPColorExtractor;

use Exception;
use GdImage;

/**
 * A simple class to extract the main colour palette of an image
 *
 * Class PHPColorExtractor
 * @package PHPColorExtractor
 */
class PHPColorExtractor
{
    protected ?string $imagePath = null;
    private ?GdImage $gdImage = null;
    protected int $totalColors = 10;
    protected int $granularity = 5;

    /**
     * Set the image we want to extract the colours from
     *
     * @param string $image Path to the image file
     * @return $this
     * @throws Exception
     */
    public function setImage(string $image): self
    {
        if (!is_file($image)) {
            throw new Exception('Unable to find provided image');
        }

        $this->imagePath = $image;
        $this->gdImage = null;

        return $this;
    }

    /**
     * Set the image from raw image data, e.g. an upload, a storage service
     * response or the body of an HTTP request you made yourself
     *
     * @param string $data Binary image data in any format supported by GD
     * @return $this
     * @throws Exception
     */
    public function setImageFromString(string $data): self
    {
        return $this->setImageFromGd(
            $this->decodeImage($data, 'Unable to read provided image data', 'Unable to decode provided image data'),
        );
    }

    /**
     * Set the image from an existing GD image
     *
     * @param GdImage $image
     * @return $this
     */
    public function setImageFromGd(GdImage $image): self
    {
        $this->gdImage = $image;
        $this->imagePath = null;

        return $this;
    }

    /**
     * Sets the total number of colours to return
     *
     * @param int $totalColors
     * @return $this
     * @throws Exception
     */
    public function setTotalColors(int $totalColors = 10): self
    {
        if ($totalColors <= 0) {
            throw new Exception('Invalid total Colors: ' . $totalColors);
        }

        $this->totalColors = $totalColors;

        return $this;
    }

    /**
     * Sets the granularity to determine the contained colours
     *
     * @param int $granularity
     * @return $this
     * @throws Exception
     */
    public function setGranularity(int $granularity = 5): self
    {
        if ($granularity <= 0) {
            throw new Exception('Invalid granularity: ' . $granularity);
        }

        $this->granularity = max(1, abs($granularity));

        return $this;
    }

    /**
     * Extracts the colour palette of the set image
     *
     * @return array<int, string>
     * @throws Exception
     */
    public function extractPalette(): array
    {
        if ($this->gdImage === null && $this->imagePath === null) {
            throw new Exception('An image must be set before its palette can be extracted.');
        }

        $img = $this->gdImage ?? $this->loadImageFromPath($this->imagePath);
        $width = imagesx($img);
        $height = imagesy($img);

        $colors = [];

        for ($x = 0; $x < $width; $x += $this->granularity) {
            for ($y = 0; $y < $height; $y += $this->granularity) {
                $colorIndex = imagecolorat($img, $x, $y);
                if ($colorIndex === false) {
                    continue;
                }

                $rgb = imagecolorsforindex($img, $colorIndex);
                if ($rgb['alpha'] === 127) {
                    // Fully transparent pixels have no visible colour
                    continue;
                }

                $red = (int) round(round($rgb['red'] / 0x33) * 0x33);
                $green = (int) round(round($rgb['green'] / 0x33) * 0x33);
                $blue = (int) round(round($rgb['blue'] / 0x33) * 0x33);
                $thisRGB = \sprintf('%02X%02X%02X', $red, $green, $blue);

                if (\array_key_exists($thisRGB, $colors)) {
                    $colors[$thisRGB]++;
                } else {
                    $colors[$thisRGB] = 1;
                }
            }
        }

        arsort($colors);

        // Hex values made only of digits (e.g. "999999") become integer array keys, so cast back
        return array_map(strval(...), \array_slice(array_keys($colors), 0, $this->totalColors));
    }

    /**
     * Loads the image set via setImage()
     *
     * @throws Exception
     */
    private function loadImageFromPath(string $path): GdImage
    {
        $imageContent = file_get_contents($path);
        if ($imageContent === false) {
            throw new Exception('Unable to read image file');
        }

        return $this->decodeImage($imageContent, 'Unable to get image size data', 'Unable to open image file');
    }

    /**
     * Decodes binary image data, throwing the given messages if it isn't a
     * recognised image or GD can't decode it
     *
     * @throws Exception
     */
    private function decodeImage(string $data, string $unrecognisedMessage, string $decodeFailedMessage): GdImage
    {
        if ($data === '' || getimagesizefromstring($data) === false) {
            throw new Exception($unrecognisedMessage);
        }

        $img = imagecreatefromstring($data);
        if ($img === false) {
            throw new Exception($decodeFailedMessage);
        }

        return $img;
    }
}
