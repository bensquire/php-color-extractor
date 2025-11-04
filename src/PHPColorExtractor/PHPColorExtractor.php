<?php

declare(strict_types=1);

namespace PHPColorExtractor;

use Exception;

/**
 * A simple class to extract the main colour palette of an image
 *
 * Class PHPColorExtractor
 * @package PHPColorExtractor
 */
class PHPColorExtractor
{
    protected ?string $image = null;
    protected int $totalColors = 10;
    protected int $granularity = 5;

    /**
     * Set the image we want to extract the colours from
     *
     * @param string $image
     * @return $this
     * @throws Exception
     */
    public function setImage(string $image): self
    {
        if (!file_exists($image)) {
            throw new Exception('Unable to find provided image');
        }

        $this->image = $image;
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
        if ($this->image === null) {
            throw new Exception('An image must be set before its palette can be extracted.');
        }

        $size = getimagesize($this->image);
        if ($size === false) {
            throw new Exception("Unable to get image size data");
        }

        $imageContent = file_get_contents($this->image);
        if ($imageContent === false) {
            throw new Exception("Unable to read image file");
        }

        $img = imagecreatefromstring($imageContent);
        if ($img === false) {
            throw new Exception("Unable to open image file");
        }

        $colors = [];

        for ($x = 0; $x < $size[0]; $x += $this->granularity) {
            for ($y = 0; $y < $size[1]; $y += $this->granularity) {
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x, $y));
                $red = (int)round(round($rgb['red'] / 0x33) * 0x33);
                $green = (int)round(round($rgb['green'] / 0x33) * 0x33);
                $blue = (int)round(round($rgb['blue'] / 0x33) * 0x33);
                $thisRGB = sprintf('%02X%02X%02X', $red, $green, $blue);

                if (array_key_exists($thisRGB, $colors)) {
                    $colors[$thisRGB]++;
                } else {
                    $colors[$thisRGB] = 1;
                }
            }
        }

        arsort($colors);
        return array_slice(array_keys($colors), 0, $this->totalColors);
    }
}
