<?php

/*
 * todo: allow for multiple layers and get photoshop functionality
 */
namespace system\helpers;

class MS_image
{
	private $mainImage;    //GD image
	private $mainImageSize;    //image sizes
	private $mainFont;    //font location to be used
	private $mainColor;

	function __construct() {
		$this->setFont('default');
	}

	public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return $this->$name;
	}

	/**
	 * @param int $red   : rgb color for red
	 * @param int $green : rgb color for green
	 * @param int $blue  : rgb color for blue
	 */
	public function setColor($red = 0, $green = 0, $blue = 0) {
		$im              = imagecreatetruecolor(1, 1);
		$this->mainColor = imagecolorallocate($im, $red, $green, $blue);
	}

	/**
	 * @param $file : the image file to use
	 *
	 * @throws \Exception: thrown when the image isn't gif, jpeg, or png
	 */
	public function setImage($file) {
		$this->mainImageSize = getimagesize($file);
		switch($this->mainImageSize[2]) {
			case IMG_PNG:
				$this->mainImage = imagecreatefrompng($file);
				break;
			case IMG_JPEG:
				$this->mainImage = imagecreatefromjpeg($file);
				break;
			case IMG_GIF:
				$this->mainImage = imagecreatefromgif($file);
				break;
			default:
				throw new \Exception('this image type is not supported: ' . $this->mainImage);
				break;
		}
	}

	/**
	 * @param      $font     : the font that we use
	 * @param bool $absolute : to toggle that we use the absolute path or the relative path
	 */
	public function setFont($font, $absolute = FALSE) {
		if($absolute == TRUE) {
			$this->mainFont = $font;
		}
		else {
			$this->mainFont = './public/fonts/' . $font . '.ttf';
		}
	}

	/**
	 * @param      $string    : the string to use
	 * @param int  $size      : the font size
	 * @param int  $x         : the x coordinate
	 * @param null $y         : the y coordinate
	 * @param int  $direction : the direction to write in degrees
	 */
	public function setWaterMark($string, $size = 12, $x = 0, $y = NULL, $direction = 0) {
		if(is_null($y)) {
			$y = $size;
		}
		imagettftext($this->mainImage, $size, $direction, $x, $y, $this->mainColor, $this->mainFont, $string);
	}

	/**
	 * @param        $name
	 * @param string $type
	 * @param        $quality
	 *
	 * @return bool: $the image that we return
	 * @throws \Exception: in case the type doesn't match we throw an exception
	 */
	public function returnImage($name, $type = 'gif', $quality) {
		switch($type) {
			case 'png':
				return imagepng($this->mainImage, $name, $quality);
				break;
			case 'jpeg':
				return imagejpeg($this->mainImage, $name, $quality);
				break;
			case 'gif':
				return imagegif($this->mainImage, $name);
				break;
			default:
				throw new \Exception('This image type is not supported');
				break;
		}
	}

	/**
	 *    we make a screen shot of the primary display of the server only works on windows
	 */
	public function screenShot() {
		$this->mainImage = imagegrabscreen();
	}

	/**
	 * @param $width  : the new width to use
	 * @param $height : the new height to use
	 */
	public function resize($width, $height) {
		$image_new = imagecreatetruecolor($width, $height);

		imagecopyresampled($image_new, $this->mainImage, 0, 0, 0, 0, $width, $height, $this->mainImageSize[0], $this->mainImageSize[1]);
		$this->mainImage = $image_new;

		$this->mainImageSize[0] = $width;
		$this->mainImageSize[1] = $height;
		$this->mainImageSize[3] = 'width="'.$width.'" height="'.$height.'"';
	}

	/**
	 * we will resize the image and keep the original proportions
	 *
	 * @param int    $size : size in pixels
	 * @param string $type : width or height
	 */
	public function scale($size, $type = 'width') {
		$proportion = $this->mainImageSize[0] / $this->mainImageSize[1];
		if($type == 'width') {
			$this->resize($size, round($size / $proportion));
		}
		elseif($type == 'height') {
			$this->resize(round($size * $proportion), $size);
		}
	}

	/**
	 * @param       $filterType : the filter type to apply to the image
	 * @param array $parameters : the parameters to use for the filter : level, r,g,b
	 *
	 * @throws \Exception: in case the request filter doesn't exist we throw an error
	 */
	public function applyFilter($filterType, $parameters = [NULL]) {
		switch($filterType) {
			case 'greyscale':
				imagefilter($this->mainImage, IMG_FILTER_GRAYSCALE);
				break;
			case 'brightness':
				imagefilter($this->mainImage, IMG_FILTER_BRIGHTNESS, $parameters['level']);
				break;
			case 'colorize':
				imagefilter($this->mainImage, IMG_FILTER_COLORIZE, $parameters['r'], $parameters['g'], $parameters['b']);
				break;
			case 'negate':
				imagefilter($this->mainImage, IMG_FILTER_NEGATE);
				break;
			case 'pixelate':
				imagefilter($this->mainImage, IMG_FILTER_PIXELATE, $parameters['level'], TRUE);
				break;
			case 'edge':
				imagefilter($this->mainImage, IMG_FILTER_EDGEDETECT);
				break;
			case 'emboss':
				imagefilter($this->mainImage, IMG_FILTER_EMBOSS);
				break;
			case 'gaussian':
				imagefilter($this->mainImage, IMG_FILTER_GAUSSIAN_BLUR);
				break;
			case 'blur':
				imagefilter($this->mainImage, IMG_FILTER_SELECTIVE_BLUR);
				break;
			case 'sketchy':
				imagefilter($this->mainImage, IMG_FILTER_MEAN_REMOVAL);
				break;
			case 'smooth':
				imagefilter($this->mainImage, IMG_FILTER_SMOOTH, $parameters['level']);
				break;
			case 'contrast':
				imagefilter($this->mainImage, IMG_FILTER_CONTRAST, $parameters['level']);
				break;
			default: {
				throw new \Exception('This filter is not supported');
				break;
			}
		}
	}

	/**
	 * @param $mode : the mode used for the flip: horizontal,vertical, and both are supported
	 *
	 * @throws \Exception: if the mode is not supported we throw an exception
	 */
	public function flip($mode) {
		switch($mode) {
			case 'horizontal':
				imageflip($this->mainImage, IMG_FLIP_HORIZONTAL);
				break;
			case 'vertical':
				imageflip($this->mainImage, IMG_FLIP_VERTICAL);
				break;
			case 'both':
				imageflip($this->mainImage, IMG_FLIP_BOTH);
				break;
			default:
				throw new \Exception('This mode is not supported');
				break;
		}
	}

	/**
	 * @param bool $mode : whether to enable the blending mode or not
	 */
	public function setBlendingMode($mode = TRUE) {
		imagealphablending($this->mainImage, $mode);
	}

	/**
	 * @param $mode : the mode to use for the auto crop: transparent, black, white, and sides
	 *
	 * @throws \Exception: if the mode is not supported we throw an exception
	 */
	public function autoCrop($mode) {
		switch($mode) {
			case 'transparent':
				imagecropauto($this->mainImage, IMG_CROP_TRANSPARENT);
				break;
			case 'black':
				imagecropauto($this->mainImage, IMG_CROP_BLACK);
				break;
			case 'white':
				imagecropauto($this->mainImage, IMG_CROP_WHITE);
				break;
			case 'sides':
				imagecropauto($this->mainImage, IMG_CROP_SIDES);
				break;
			default:
				throw new \Exception('This mode is not supported');
				break;
		}
	}
}