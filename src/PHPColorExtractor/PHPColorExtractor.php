<?php
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
    protected $image;
    protected $totalColors = 10;
    protected $granularity = 5;

    /**
     * Set the image we want to extract the colours from
     *
     * @param $image
     * @return $this
     * @throws Exception
     */
    public function setImage($image)
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
    public function setTotalColors($totalColors = 10)
    {
        if (!is_int($totalColors))
        {
            throw new Exception('Invalid total Colors: ' . $totalColors);
        }

        $this->totalColors = (int)$totalColors;
        return $this;
    }

    /**
     * Sets the granularity to determine the contained colours
     *
     * @param int $granularity
     * @return $this
     * @throws Exception
     */
    public function setGranularity($granularity = 5)
    {
        if (!is_int($granularity))
        {
            throw new Exception('Invalid total Colors: ' . $granularity);
        }

        $this->granularity = max(1, abs((int)$granularity));
        return $this;
    }

    /**
     * Extracts the colour palette of the set image
     *
     * @return array
     * @throws Exception
     */
    public function extractPalette()
    {
        if (is_null($this->image)) {
            throw new Exception ('An image must be set before its palette can be extracted.');
        }

        if (($size = getimagesize($this->image)) === false) {
            throw new Exception("Unable to get image size data");
        }

        if (($img = imagecreatefromstring(file_get_contents($this->image))) === false) {
            throw new Exception("Unable to open image file");
        }

        $colors = array();

        for ($x = 0; $x < $size[0]; $x += $this->granularity) {
            for ($y = 0; $y < $size[1]; $y += $this->granularity) {
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x, $y));
                $red = round(round(($rgb['red'] / 0x33)) * 0x33);
                $green = round(round(($rgb['green'] / 0x33)) * 0x33);
                $blue = round(round(($rgb['blue'] / 0x33)) * 0x33);
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
