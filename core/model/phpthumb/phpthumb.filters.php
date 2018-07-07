<?php
//////////////////////////////////////////////////////////////
//   phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net      //
//         and/or https://github.com/JamesHeinrich/phpThumb //
//////////////////////////////////////////////////////////////
///                                                         //
// phpthumb.filters.php - image processing filter functions //
//                                                         ///
//////////////////////////////////////////////////////////////

class phpthumb_filters {

	/**
	* @var phpthumb
	*/

	public $phpThumbObject = null;


	public function DebugMessage($message, $file='', $line='') {
		if (is_object($this->phpThumbObject)) {
			return $this->phpThumbObject->DebugMessage($message, $file, $line);
		}
		return false;
	}


	public function ApplyMask(&$gdimg_mask, &$gdimg_image) {
		if (phpthumb_functions::gd_version() < 2) {
			$this->DebugMessage('Skipping ApplyMask() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
			return false;
		}
		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '4.3.2', '>=')) {

			$this->DebugMessage('Using alpha ApplyMask() technique', __FILE__, __LINE__);
			if ($gdimg_mask_resized = phpthumb_functions::ImageCreateFunction(imagesx($gdimg_image), imagesy($gdimg_image))) {

				imagecopyresampled($gdimg_mask_resized, $gdimg_mask, 0, 0, 0, 0, imagesx($gdimg_image), imagesy($gdimg_image), imagesx($gdimg_mask), imagesy($gdimg_mask));
				if ($gdimg_mask_blendtemp = phpthumb_functions::ImageCreateFunction(imagesx($gdimg_image), imagesy($gdimg_image))) {

					$color_background = imagecolorallocate($gdimg_mask_blendtemp, 0, 0, 0);
					imagefilledrectangle($gdimg_mask_blendtemp, 0, 0, imagesx($gdimg_mask_blendtemp), imagesy($gdimg_mask_blendtemp), $color_background);
					imagealphablending($gdimg_mask_blendtemp, false);
					imagesavealpha($gdimg_mask_blendtemp, true);
					for ($x = 0, $xMax = imagesx($gdimg_image); $x < $xMax; $x++) {
						for ($y = 0, $yMax = imagesy($gdimg_image); $y < $yMax; $y++) {
							//$RealPixel = phpthumb_functions::GetPixelColor($gdimg_mask_blendtemp, $x, $y);
							$RealPixel = phpthumb_functions::GetPixelColor($gdimg_image, $x, $y);
							$MaskPixel = phpthumb_functions::GrayscalePixel(phpthumb_functions::GetPixelColor($gdimg_mask_resized, $x, $y));
							$MaskAlpha = 127 - (floor($MaskPixel['red'] / 2) * (1 - ($RealPixel['alpha'] / 127)));
							$newcolor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_mask_blendtemp, $RealPixel['red'], $RealPixel['green'], $RealPixel['blue'], $MaskAlpha);
							imagesetpixel($gdimg_mask_blendtemp, $x, $y, $newcolor);
						}
					}
					imagealphablending($gdimg_image, false);
					imagesavealpha($gdimg_image, true);
					imagecopy($gdimg_image, $gdimg_mask_blendtemp, 0, 0, 0, 0, imagesx($gdimg_mask_blendtemp), imagesy($gdimg_mask_blendtemp));
					imagedestroy($gdimg_mask_blendtemp);

				} else {
					$this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
				}
				imagedestroy($gdimg_mask_resized);

			} else {
				$this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
			}

		} else {
			// alpha merging requires PHP v4.3.2+
			$this->DebugMessage('Skipping ApplyMask() technique because PHP is v"'. PHP_VERSION .'"', __FILE__, __LINE__);
		}
		return true;
	}


    public function Bevel(&$gdimg, $width, $hexcolor1, $hexcolor2) {
        $width     = ($width     ? $width     : 5);
        $hexcolor1 = ($hexcolor1 ? $hexcolor1 : 'FFFFFF');
        $hexcolor2 = ($hexcolor2 ? $hexcolor2 : '000000');

        imagealphablending($gdimg, true);
        for ($i = 0; $i < $width; $i++) {
            $alpha = round(($i / $width) * 127);
            $color1 = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor1, false, $alpha);
            $color2 = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor2, false, $alpha);

            imageline($gdimg,                   $i,                   $i + 1,                   $i, imagesy($gdimg) - $i - 1, $color1); // left
            imageline($gdimg,                   $i,                   $i    , imagesx($gdimg) - $i,                   $i    , $color1); // top
            imageline($gdimg, imagesx($gdimg) - $i, imagesy($gdimg) - $i - 1, imagesx($gdimg) - $i,                   $i + 1, $color2); // right
            imageline($gdimg, imagesx($gdimg) - $i, imagesy($gdimg) - $i    ,                   $i, imagesy($gdimg) - $i    , $color2); // bottom
        }
        return true;
    }


	public function Blur(&$gdimg, $radius=0.5) {
		// Taken from Torstein Hønsi's phpUnsharpMask (see phpthumb.unsharp.php)

		$radius = round(max(0, min($radius, 50)) * 2);
		if (!$radius) {
			return false;
		}

		$w = imagesx($gdimg);
		$h = imagesy($gdimg);
		if ($imgBlur = imagecreatetruecolor($w, $h)) {
			// Gaussian blur matrix:
			//	1	2	1
			//	2	4	2
			//	1	2	1

			// Move copies of the image around one pixel at the time and merge them with weight
			// according to the matrix. The same matrix is simply repeated for higher radii.
			for ($i = 0; $i < $radius; $i++)	{
				imagecopy     ($imgBlur, $gdimg, 0, 0, 1, 1, $w - 1, $h - 1);            // up left
				imagecopymerge($imgBlur, $gdimg, 1, 1, 0, 0, $w,     $h,     50.00000);  // down right
				imagecopymerge($imgBlur, $gdimg, 0, 1, 1, 0, $w - 1, $h,     33.33333);  // down left
				imagecopymerge($imgBlur, $gdimg, 1, 0, 0, 1, $w,     $h - 1, 25.00000);  // up right
				imagecopymerge($imgBlur, $gdimg, 0, 0, 1, 0, $w - 1, $h,     33.33333);  // left
				imagecopymerge($imgBlur, $gdimg, 1, 0, 0, 0, $w,     $h,     25.00000);  // right
				imagecopymerge($imgBlur, $gdimg, 0, 0, 0, 1, $w,     $h - 1, 20.00000);  // up
				imagecopymerge($imgBlur, $gdimg, 0, 1, 0, 0, $w,     $h,     16.666667); // down
				imagecopymerge($imgBlur, $gdimg, 0, 0, 0, 0, $w,     $h,     50.000000); // center
				imagecopy     ($gdimg, $imgBlur, 0, 0, 0, 0, $w,     $h);
			}
			return true;
		}
		return false;
	}


	public function BlurGaussian(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_GAUSSIAN_BLUR)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_GAUSSIAN_BLUR)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		$this->DebugMessage('FAILED: phpthumb_filters::BlurGaussian($gdimg) [using phpthumb_filters::Blur() instead]', __FILE__, __LINE__);
		return self::Blur($gdimg, 0.5);
	}


	public function BlurSelective(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_SELECTIVE_BLUR)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_SELECTIVE_BLUR)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::BlurSelective($gdimg) [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	public function Brightness(&$gdimg, $amount=0) {
		if ($amount == 0) {
			return true;
		}
		$amount = max(-255, min(255, $amount));

		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_BRIGHTNESS, $amount)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_BRIGHTNESS, '.$amount.')', __FILE__, __LINE__);
			// fall through and try it the hard way
		}

		$scaling = (255 - abs($amount)) / 255;
		$baseamount = (($amount > 0) ? $amount : 0);
		for ($x = 0, $xMax = imagesx($gdimg); $x < $xMax; $x++) {
			for ($y = 0, $yMax = imagesy($gdimg); $y < $yMax; $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$NewPixel = array();
				foreach ($OriginalPixel as $key => $value) {
					$NewPixel[$key] = round($baseamount + ($OriginalPixel[$key] * $scaling));
				}
				$newColor = imagecolorallocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
				imagesetpixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	public function Contrast(&$gdimg, $amount=0) {
		if ($amount == 0) {
			return true;
		}
		$amount = max(-255, min(255, $amount));

		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			// imagefilter(IMG_FILTER_CONTRAST) has range +100 to -100 (positive numbers make it darker!)
			$amount = ($amount / 255) * -100;
			if (imagefilter($gdimg, IMG_FILTER_CONTRAST, $amount)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_CONTRAST, '.$amount.')', __FILE__, __LINE__);
			// fall through and try it the hard way
		}

		if ($amount > 0) {
			$scaling = 1 + ($amount / 255);
		} else {
			$scaling = (255 - abs($amount)) / 255;
		}
		for ($x = 0, $xMax = imagesx($gdimg); $x < $xMax; $x++) {
			for ($y = 0, $yMax = imagesy($gdimg); $y < $yMax; $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$NewPixel = array();
				foreach ($OriginalPixel as $key => $value) {
					$NewPixel[$key] = min(255, max(0, round($OriginalPixel[$key] * $scaling)));
				}
				$newColor = imagecolorallocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
				imagesetpixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	public function Colorize(&$gdimg, $amount, $targetColor) {
		$amount      = (is_numeric($amount)                          ? $amount      : 25);
		$amountPct   = $amount / 100;
		$targetColor = (phpthumb_functions::IsHexColor($targetColor) ? $targetColor : 'gray');

		if ($amount == 0) {
			return true;
		}

		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if ($targetColor == 'gray') {
				$targetColor = '808080';
			}
			$r = round($amountPct * hexdec(substr($targetColor, 0, 2)));
			$g = round($amountPct * hexdec(substr($targetColor, 2, 2)));
			$b = round($amountPct * hexdec(substr($targetColor, 4, 2)));
			if (imagefilter($gdimg, IMG_FILTER_COLORIZE, $r, $g, $b)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_COLORIZE)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}

		// overridden below for grayscale
		$TargetPixel = array();
		if ($targetColor != 'gray') {
			$TargetPixel['red']   = hexdec(substr($targetColor, 0, 2));
			$TargetPixel['green'] = hexdec(substr($targetColor, 2, 2));
			$TargetPixel['blue']  = hexdec(substr($targetColor, 4, 2));
		}

		for ($x = 0, $xMax = imagesx($gdimg); $x < $xMax; $x++) {
			for ($y = 0, $yMax = imagesy($gdimg); $y < $yMax; $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				if ($targetColor == 'gray') {
					$TargetPixel = phpthumb_functions::GrayscalePixel($OriginalPixel);
				}
				$NewPixel = array();
				foreach ($TargetPixel as $key => $value) {
					$NewPixel[$key] = round(max(0, min(255, ($OriginalPixel[$key] * ((100 - $amount) / 100)) + ($TargetPixel[$key] * $amountPct))));
				}
				//$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue'], $OriginalPixel['alpha']);
				$newColor = imagecolorallocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
				imagesetpixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	public function Crop(&$gdimg, $left=0, $right=0, $top=0, $bottom=0) {
		if (!$left && !$right && !$top && !$bottom) {
			return true;
		}
		$oldW = imagesx($gdimg);
		$oldH = imagesy($gdimg);
		if (($left   > 0) && ($left   < 1)) { $left   = round($left   * $oldW); }
		if (($right  > 0) && ($right  < 1)) { $right  = round($right  * $oldW); }
		if (($top    > 0) && ($top    < 1)) { $top    = round($top    * $oldH); }
		if (($bottom > 0) && ($bottom < 1)) { $bottom = round($bottom * $oldH); }
		$right  = min($oldW - $left - 1, $right);
		$bottom = min($oldH - $top  - 1, $bottom);
		$newW = $oldW - $left - $right;
		$newH = $oldH - $top  - $bottom;

		if ($imgCropped = imagecreatetruecolor($newW, $newH)) {
			imagecopy($imgCropped, $gdimg, 0, 0, $left, $top, $newW, $newH);
			if ($gdimg = imagecreatetruecolor($newW, $newH)) {
				imagecopy($gdimg, $imgCropped, 0, 0, 0, 0, $newW, $newH);
				imagedestroy($imgCropped);
				return true;
			}
			imagedestroy($imgCropped);
		}
		return false;
	}


	public function Desaturate(&$gdimg, $amount, $color='') {
		if ($amount == 0) {
			return true;
		}
		return self::Colorize($gdimg, $amount, (phpthumb_functions::IsHexColor($color) ? $color : 'gray'));
	}


	public function DropShadow(&$gdimg, $distance, $width, $hexcolor, $angle, $alpha) {
		if (phpthumb_functions::gd_version() < 2) {
			return false;
		}
		$distance =                 ($distance ? $distance : 10);
		$width    =                 ($width    ? $width    : 10);
		$hexcolor =                 ($hexcolor ? $hexcolor : '000000');
		$angle    =                 ($angle    ? $angle    : 225) % 360;
		$alpha    = max(0, min(100, ($alpha    ? $alpha    : 100)));

		if ($alpha <= 0) {
			// invisible shadow, nothing to do
			return true;
		}
		if ($distance <= 0) {
			// shadow completely obscured by source image, nothing to do
			return true;
		}

		//$width_shadow  = cos(deg2rad($angle)) * ($distance + $width);
		//$height_shadow = sin(deg2rad($angle)) * ($distance + $width);
		//$scaling = min(imagesx($gdimg) / (imagesx($gdimg) + abs($width_shadow)), imagesy($gdimg) / (imagesy($gdimg) + abs($height_shadow)));

		$Offset = array();
		for ($i = 0; $i < $width; $i++) {
			$WidthAlpha[$i] = (abs(($width / 2) - $i) / $width);
			$Offset['x'] = cos(deg2rad($angle)) * ($distance + $i);
			$Offset['y'] = sin(deg2rad($angle)) * ($distance + $i);
		}

		$tempImageWidth  = imagesx($gdimg)  + abs($Offset['x']);
		$tempImageHeight = imagesy($gdimg) + abs($Offset['y']);

		if ($gdimg_dropshadow_temp = phpthumb_functions::ImageCreateFunction($tempImageWidth, $tempImageHeight)) {

			imagealphablending($gdimg_dropshadow_temp, false);
			imagesavealpha($gdimg_dropshadow_temp, true);
			$transparent1 = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_dropshadow_temp, 0, 0, 0, 127);
			imagefill($gdimg_dropshadow_temp, 0, 0, $transparent1);

			$PixelMap = array();
			for ($x = 0, $xMax = imagesx($gdimg); $x < $xMax; $x++) {
				for ($y = 0, $yMax = imagesy($gdimg); $y < $yMax; $y++) {
					$PixelMap[$x][$y] = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				}
			}
			for ($x = 0; $x < $tempImageWidth; $x++) {
				for ($y = 0; $y < $tempImageHeight; $y++) {
					//for ($i = 0; $i < $width; $i++) {
					for ($i = 0; $i < 1; $i++) {
						if (!isset($PixelMap[$x][$y]['alpha']) || ($PixelMap[$x][$y]['alpha'] > 0)) {
							if (isset($PixelMap[$x + $Offset['x']][$y + $Offset['y']]['alpha']) && ($PixelMap[$x + $Offset['x']][$y + $Offset['y']]['alpha'] < 127)) {
								$thisColor = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor, false, $PixelMap[$x + $Offset['x']][$y + $Offset['y']]['alpha']);
								imagesetpixel($gdimg_dropshadow_temp, $x, $y, $thisColor);
							}
						}
					}
				}
			}

			imagealphablending($gdimg_dropshadow_temp, true);
			for ($x = 0, $xMax = imagesx($gdimg); $x < $xMax; $x++) {
				for ($y = 0, $yMax = imagesy($gdimg); $y < $yMax; $y++) {
					if ($PixelMap[$x][$y]['alpha'] < 127) {
						$thisColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_dropshadow_temp, $PixelMap[$x][$y]['red'], $PixelMap[$x][$y]['green'], $PixelMap[$x][$y]['blue'], $PixelMap[$x][$y]['alpha']);
						imagesetpixel($gdimg_dropshadow_temp, $x, $y, $thisColor);
					}
				}
			}

			imagesavealpha($gdimg, true);
			imagealphablending($gdimg, false);
			//$this->is_alpha = true;
			$transparent2 = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 0, 0, 0, 127);
			imagefilledrectangle($gdimg, 0, 0, imagesx($gdimg), imagesy($gdimg), $transparent2);
			imagecopyresampled($gdimg, $gdimg_dropshadow_temp, 0, 0, 0, 0, imagesx($gdimg), imagesy($gdimg), imagesx($gdimg_dropshadow_temp), imagesy($gdimg_dropshadow_temp));

			imagedestroy($gdimg_dropshadow_temp);
		}
		return true;
	}


	public function EdgeDetect(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_EDGEDETECT)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_EDGEDETECT)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::EdgeDetect($gdimg) [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	public function Ellipse($gdimg) {
		if (phpthumb_functions::gd_version() < 2) {
			return false;
		}
		// generate mask at twice desired resolution and downsample afterwards for easy antialiasing
		if ($gdimg_ellipsemask_double = phpthumb_functions::ImageCreateFunction(imagesx($gdimg) * 2, imagesy($gdimg) * 2)) {
			if ($gdimg_ellipsemask = phpthumb_functions::ImageCreateFunction(imagesx($gdimg), imagesy($gdimg))) {

				$color_transparent = imagecolorallocate($gdimg_ellipsemask_double, 255, 255, 255);
				imagefilledellipse($gdimg_ellipsemask_double, imagesx($gdimg), imagesy($gdimg), (imagesx($gdimg) - 1) * 2, (imagesy($gdimg) - 1) * 2, $color_transparent);
				imagecopyresampled($gdimg_ellipsemask, $gdimg_ellipsemask_double, 0, 0, 0, 0, imagesx($gdimg), imagesy($gdimg), imagesx($gdimg) * 2, imagesy($gdimg) * 2);

				self::ApplyMask($gdimg_ellipsemask, $gdimg);
				imagedestroy($gdimg_ellipsemask);
				return true;

			} else {
				$this->DebugMessage('$gdimg_ellipsemask = phpthumb_functions::ImageCreateFunction() failed', __FILE__, __LINE__);
			}
			imagedestroy($gdimg_ellipsemask_double);
		} else {
			$this->DebugMessage('$gdimg_ellipsemask_double = phpthumb_functions::ImageCreateFunction() failed', __FILE__, __LINE__);
		}
		return false;
	}


	public function Emboss(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_EMBOSS)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_EMBOSS)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::Emboss($gdimg) [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	public function Flip(&$gdimg, $x=false, $y=false) {
		if (!$x && !$y) {
			return false;
		}
		if ($tempImage = phpthumb_functions::ImageCreateFunction(imagesx($gdimg), imagesy($gdimg))) {
			if ($x) {
				imagecopy($tempImage, $gdimg, 0, 0, 0, 0, imagesx($gdimg), imagesy($gdimg));
				for ($x = 0, $xMax = imagesx($gdimg); $x < $xMax; $x++) {
					imagecopy($gdimg, $tempImage, imagesx($gdimg) - 1 - $x, 0, $x, 0, 1, imagesy($gdimg));
				}
			}
			if ($y) {
				imagecopy($tempImage, $gdimg, 0, 0, 0, 0, imagesx($gdimg), imagesy($gdimg));
				for ($y = 0, $yMax = imagesy($gdimg); $y < $yMax; $y++) {
					imagecopy($gdimg, $tempImage, 0, imagesy($gdimg) - 1 - $y, 0, $y, imagesx($gdimg), 1);
				}
			}
			imagedestroy($tempImage);
		}
		return true;
	}


	public function Frame(&$gdimg, $frame_width, $edge_width, $hexcolor_frame, $hexcolor1, $hexcolor2) {
		$frame_width    = ($frame_width    ? $frame_width    : 5);
		$edge_width     = ($edge_width     ? $edge_width     : 1);
		$hexcolor_frame = ($hexcolor_frame ? $hexcolor_frame : 'CCCCCC');
		$hexcolor1      = ($hexcolor1      ? $hexcolor1      : 'FFFFFF');
		$hexcolor2      = ($hexcolor2      ? $hexcolor2      : '000000');

		$color_frame = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor_frame);
		$color1      = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor1);
		$color2      = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor2);
		for ($i = 0; $i < $edge_width; $i++) {
			// outer bevel
			imageline($gdimg,                   $i,                   $i,                   $i, imagesy($gdimg) - $i, $color1); // left
			imageline($gdimg,                   $i,                   $i, imagesx($gdimg) - $i,                   $i, $color1); // top
			imageline($gdimg, imagesx($gdimg) - $i, imagesy($gdimg) - $i, imagesx($gdimg) - $i,                   $i, $color2); // right
			imageline($gdimg, imagesx($gdimg) - $i, imagesy($gdimg) - $i,                   $i, imagesy($gdimg) - $i, $color2); // bottom
		}
		for ($i = 0; $i < $frame_width; $i++) {
			// actual frame
			imagerectangle($gdimg, $edge_width + $i, $edge_width + $i, imagesx($gdimg) - $edge_width - $i, imagesy($gdimg) - $edge_width - $i, $color_frame);
		}
		for ($i = 0; $i < $edge_width; $i++) {
			// inner bevel
			imageline($gdimg,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i, imagesy($gdimg) - $frame_width - $edge_width - $i, $color2); // left
			imageline($gdimg,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i, imagesx($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, $color2); // top
			imageline($gdimg, imagesx($gdimg) - $frame_width - $edge_width - $i, imagesy($gdimg) - $frame_width - $edge_width - $i, imagesx($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, $color1); // right
			imageline($gdimg, imagesx($gdimg) - $frame_width - $edge_width - $i, imagesy($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, imagesy($gdimg) - $frame_width - $edge_width - $i, $color1); // bottom
		}
		return true;
	}


	public function Gamma(&$gdimg, $amount) {
		if (number_format($amount, 4) == '1.0000') {
			return true;
		}
		return imagegammacorrect($gdimg, 1.0, $amount);
	}


	public function Grayscale(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_GRAYSCALE)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_GRAYSCALE)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		return self::Colorize($gdimg, 100, 'gray');
	}


	public function HistogramAnalysis(&$gdimg, $calculateGray=false) {
		$ImageSX = imagesx($gdimg);
		$ImageSY = imagesy($gdimg);
		$Analysis = array();
		for ($x = 0; $x < $ImageSX; $x++) {
			for ($y = 0; $y < $ImageSY; $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				@$Analysis['red'][$OriginalPixel['red']]++;
				@$Analysis['green'][$OriginalPixel['green']]++;
				@$Analysis['blue'][$OriginalPixel['blue']]++;
				@$Analysis['alpha'][$OriginalPixel['alpha']]++;
				if ($calculateGray) {
					$GrayPixel = phpthumb_functions::GrayscalePixel($OriginalPixel);
					@$Analysis['gray'][$GrayPixel['red']]++;
				}
			}
		}
		$keys = array('red', 'green', 'blue', 'alpha');
		if ($calculateGray) {
			$keys[] = 'gray';
		}
		foreach ($keys as $dummy => $key) {
			ksort($Analysis[$key]);
		}
		return $Analysis;
	}


	public function HistogramStretch(&$gdimg, $band='*', $method=0, $threshold=0.1) {
		// equivalent of "Auto Contrast" in Adobe Photoshop
		// method 0 stretches according to RGB colors. Gives a more conservative stretch.
		// method 1 band stretches according to grayscale which is color-biased (59% green, 30% red, 11% blue). May give a punchier / more aggressive stretch, possibly appearing over-saturated
		$Analysis = self::HistogramAnalysis($gdimg, true);
		$keys = array('r'=>'red', 'g'=>'green', 'b'=>'blue', 'a'=>'alpha', '*'=>(($method == 0) ? 'all' : 'gray'));
		$band = $band[ 0 ];
		if (!isset($keys[$band])) {
			return false;
		}
		$key = $keys[$band];

		// If the absolute brightest and darkest pixels are used then one random
		// pixel in the image could throw off the whole system. Instead, count up/down
		// from the limit and allow <threshold> (default = 0.1%) of brightest/darkest
		// pixels to be clipped to min/max
		$threshold = (float) $threshold / 100;
		$clip_threshold = imagesx($gdimg) * imagesx($gdimg) * $threshold;

		$countsum  = 0;
		$range_min = 0;
		for ($i = 0; $i <= 255; $i++) {
			if ($method == 0) {
				$countsum = max(@$Analysis['red'][$i], @$Analysis['green'][$i], @$Analysis['blue'][$i]);
			} else {
				$countsum += @$Analysis[$key][$i];
			}
			if ($countsum >= $clip_threshold) {
				$range_min = $i - 1;
				break;
			}
		}
		$range_min = max($range_min, 0);

		$countsum  =   0;
		$range_max = 255;
		for ($i = 255; $i >= 0; $i--) {
			if ($method == 0) {
				$countsum = max(@$Analysis['red'][$i], @$Analysis['green'][$i], @$Analysis['blue'][$i]);
			} else {
				$countsum += @$Analysis[$key][$i];
			}
			if ($countsum >= $clip_threshold) {
				$range_max = $i + 1;
				break;
			}
		}
		$range_max = min($range_max, 255);

		$range_scale = (($range_max == $range_min) ? 1 : (255 / ($range_max - $range_min)));
		if (($range_min == 0) && ($range_max == 255)) {
			// no adjustment necessary - don't waste CPU time!
			return true;
		}

		$ImageSX = imagesx($gdimg);
		$ImageSY = imagesy($gdimg);
		for ($x = 0; $x < $ImageSX; $x++) {
			for ($y = 0; $y < $ImageSY; $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				if ($band == '*') {
					$new['red']   = min(255, max(0, ($OriginalPixel['red']   - $range_min) * $range_scale));
					$new['green'] = min(255, max(0, ($OriginalPixel['green'] - $range_min) * $range_scale));
					$new['blue']  = min(255, max(0, ($OriginalPixel['blue']  - $range_min) * $range_scale));
					$new['alpha'] = min(255, max(0, ($OriginalPixel['alpha'] - $range_min) * $range_scale));
				} else {
					$new = $OriginalPixel;
					$new[$key] = min(255, max(0, ($OriginalPixel[$key] - $range_min) * $range_scale));
				}
				$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, $new['red'], $new['green'], $new['blue'], $new['alpha']);
				imagesetpixel($gdimg, $x, $y, $newColor);
			}
		}

		return true;
	}


	public function HistogramOverlay(&$gdimg, $bands='*', $colors='', $width=0.25, $height=0.25, $alignment='BR', $opacity=50, $margin_x=5, $margin_y=null) {
		$margin_y = (null === $margin_y ? $margin_x : $margin_y);

		$Analysis = self::HistogramAnalysis($gdimg, true);
		$histW = round(($width > 1) ? min($width, imagesx($gdimg)) : imagesx($gdimg) * $width);
		$histH = round(($width > 1) ? min($width, imagesx($gdimg)) : imagesx($gdimg) * $width);
		if ($gdHist = imagecreatetruecolor($histW, $histH)) {
			$color_back = phpthumb_functions::ImageColorAllocateAlphaSafe($gdHist, 0, 0, 0, 127);
			imagefilledrectangle($gdHist, 0, 0, $histW, $histH, $color_back);
			imagealphablending($gdHist, false);
			imagesavealpha($gdHist, true);

			$HistogramTempWidth  = 256;
			$HistogramTempHeight = 100;
			if ($gdHistTemp = imagecreatetruecolor($HistogramTempWidth, $HistogramTempHeight)) {
				$color_back_temp = phpthumb_functions::ImageColorAllocateAlphaSafe($gdHistTemp, 255, 0, 255, 127);
				imagealphablending($gdHistTemp, false);
				imagesavealpha($gdHistTemp, true);
				imagefilledrectangle($gdHistTemp, 0, 0, imagesx($gdHistTemp), imagesy($gdHistTemp), $color_back_temp);

				$DefaultColors = array('r'=>'FF0000', 'g'=>'00FF00', 'b'=>'0000FF', 'a'=>'999999', '*'=>'FFFFFF');
				$Colors = explode(';', $colors);
				$BandsToGraph = array_unique(preg_split('##', $bands));
				$keys = array('r'=>'red', 'g'=>'green', 'b'=>'blue', 'a'=>'alpha', '*'=>'gray');
				foreach ($BandsToGraph as $key => $band) {
					if (!isset($keys[$band])) {
						continue;
					}
					$PeakValue = max($Analysis[$keys[$band]]);
					$thisColor = phpthumb_functions::ImageHexColorAllocate($gdHistTemp, phpthumb_functions::IsHexColor(@$Colors[$key]) ? $Colors[$key] : $DefaultColors[$band]);
					for ($x = 0; $x < $HistogramTempWidth; $x++) {
						imageline($gdHistTemp, $x, $HistogramTempHeight - 1, $x, $HistogramTempHeight - 1 - round(@$Analysis[$keys[$band]][$x] / $PeakValue * $HistogramTempHeight), $thisColor);
					}
					imageline($gdHistTemp, 0, $HistogramTempHeight - 1, $HistogramTempWidth - 1, $HistogramTempHeight - 1, $thisColor);
					imageline($gdHistTemp, 0, $HistogramTempHeight - 2, $HistogramTempWidth - 1, $HistogramTempHeight - 2, $thisColor);
				}
				imagecopyresampled($gdHist, $gdHistTemp, 0, 0, 0, 0, imagesx($gdHist), imagesy($gdHist), imagesx($gdHistTemp), imagesy($gdHistTemp));
				imagedestroy($gdHistTemp);
			} else {
				return false;
			}

			self::WatermarkOverlay($gdimg, $gdHist, $alignment, $opacity, $margin_x, $margin_y);
			imagedestroy($gdHist);
			return true;
		}
		return false;
	}


	public function ImageBorder(&$gdimg, $border_width, $radius_x, $radius_y, $hexcolor_border) {
		$border_width = ($border_width ? $border_width : 1);
		$radius_x     = ($radius_x     ? $radius_x     : 0);
		$radius_y     = ($radius_y     ? $radius_y     : 0);

		$output_width  = imagesx($gdimg);
		$output_height = imagesy($gdimg);

		list($new_width, $new_height) = phpthumb_functions::ProportionalResize($output_width, $output_height, $output_width - max($border_width * 2, $radius_x), $output_height - max($border_width * 2, $radius_y));
		$offset_x = ($radius_x ? $output_width  - $new_width  - $radius_x : 0);

		if ($gd_border_canvas = phpthumb_functions::ImageCreateFunction($output_width, $output_height)) {

			imagesavealpha($gd_border_canvas, true);
			imagealphablending($gd_border_canvas, false);
			$color_background = phpthumb_functions::ImageColorAllocateAlphaSafe($gd_border_canvas, 255, 255, 255, 127);
			imagefilledrectangle($gd_border_canvas, 0, 0, $output_width, $output_height, $color_background);

			$color_border = phpthumb_functions::ImageHexColorAllocate($gd_border_canvas, (phpthumb_functions::IsHexColor($hexcolor_border) ? $hexcolor_border : '000000'));

			for ($i = 0; $i < $border_width; $i++) {
				imageline($gd_border_canvas,             floor($offset_x / 2) + $radius_x,                      $i, $output_width - $radius_x - ceil($offset_x / 2),                         $i, $color_border); // top
				imageline($gd_border_canvas,             floor($offset_x / 2) + $radius_x, $output_height - 1 - $i, $output_width - $radius_x - ceil($offset_x / 2),    $output_height - 1 - $i, $color_border); // bottom
				imageline($gd_border_canvas,                    floor($offset_x / 2) + $i,               $radius_y,                      floor($offset_x / 2) +  $i, $output_height - $radius_y, $color_border); // left
				imageline($gd_border_canvas, $output_width - 1 - $i - ceil($offset_x / 2),               $radius_y,    $output_width - 1 - $i - ceil($offset_x / 2), $output_height - $radius_y, $color_border); // right
			}

			if ($radius_x && $radius_y) {

				// PHP bug: imagearc() with thicknesses > 1 give bad/undesirable/unpredicatable results
				// Solution: Draw multiple 1px arcs side-by-side.

				// Problem: parallel arcs give strange/ugly antialiasing problems
				// Solution: draw non-parallel arcs, from one side of the line thickness at the start angle
				//   to the opposite edge of the line thickness at the terminating angle
				for ($thickness_offset = 0; $thickness_offset < $border_width; $thickness_offset++) {
					imagearc($gd_border_canvas, floor($offset_x / 2) + 1 +                 $radius_x,              $thickness_offset - 1 + $radius_y, $radius_x * 2, $radius_y * 2, 180, 270, $color_border); // top-left
					imagearc($gd_border_canvas,                     $output_width - $radius_x - 1 - ceil($offset_x / 2),              $thickness_offset - 1 + $radius_y, $radius_x * 2, $radius_y * 2, 270, 360, $color_border); // top-right
					imagearc($gd_border_canvas,                     $output_width - $radius_x - 1 - ceil($offset_x / 2), $output_height - $thickness_offset - $radius_y, $radius_x * 2, $radius_y * 2,   0,  90, $color_border); // bottom-right
					imagearc($gd_border_canvas, floor($offset_x / 2) + 1 +                 $radius_x, $output_height - $thickness_offset - $radius_y, $radius_x * 2, $radius_y * 2,  90, 180, $color_border); // bottom-left
				}
				if ($border_width > 1) {
					for ($thickness_offset = 0; $thickness_offset < $border_width; $thickness_offset++) {
						imagearc($gd_border_canvas, floor($offset_x / 2) + $thickness_offset + $radius_x,                                      $radius_y, $radius_x * 2, $radius_y * 2, 180, 270, $color_border); // top-left
						imagearc($gd_border_canvas, $output_width - $thickness_offset - $radius_x - 1 - ceil($offset_x / 2),                                      $radius_y, $radius_x * 2, $radius_y * 2, 270, 360, $color_border); // top-right
						imagearc($gd_border_canvas, $output_width - $thickness_offset - $radius_x - 1 - ceil($offset_x / 2),                     $output_height - $radius_y, $radius_x * 2, $radius_y * 2,   0,  90, $color_border); // bottom-right
						imagearc($gd_border_canvas, floor($offset_x / 2) + $thickness_offset + $radius_x,                     $output_height - $radius_y, $radius_x * 2, $radius_y * 2,  90, 180, $color_border); // bottom-left
					}
				}

			}
			$this->phpThumbObject->ImageResizeFunction($gd_border_canvas, $gdimg, floor(($output_width - $new_width) / 2), round(($output_height - $new_height) / 2), 0, 0, $new_width, $new_height, $output_width, $output_height);

			imagedestroy($gdimg);
			$gdimg = phpthumb_functions::ImageCreateFunction($output_width, $output_height);
			imagesavealpha($gdimg, true);
			imagealphablending($gdimg, false);
			$gdimg_color_background = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 255, 255, 255, 127);
			imagefilledrectangle($gdimg, 0, 0, $output_width, $output_height, $gdimg_color_background);

			imagecopy($gdimg, $gd_border_canvas, 0, 0, 0, 0, $output_width, $output_height);
			imagedestroy($gd_border_canvas);
			return true;


		} else {
			$this->DebugMessage('FAILED: $gd_border_canvas = phpthumb_functions::ImageCreateFunction('.$output_width.', '.$output_height.')', __FILE__, __LINE__);
		}
		return false;
	}


	public static function ImprovedImageRotate(&$gdimg_source, $rotate_angle=0, $config_background_hexcolor='FFFFFF', $bg=null, &$phpThumbObject) {
		while ($rotate_angle < 0) {
			$rotate_angle += 360;
		}
		$rotate_angle %= 360;
		if ($rotate_angle != 0) {

			$background_color = phpthumb_functions::ImageHexColorAllocate($gdimg_source, $config_background_hexcolor);

			if ((phpthumb_functions::gd_version() >= 2) && !$bg && ($rotate_angle % 90)) {

				//$this->DebugMessage('Using alpha rotate', __FILE__, __LINE__);
				if ($gdimg_rotate_mask = phpthumb_functions::ImageCreateFunction(imagesx($gdimg_source), imagesy($gdimg_source))) {

					$color_mask = array();
					for ($i = 0; $i <= 255; $i++) {
						$color_mask[$i] = imagecolorallocate($gdimg_rotate_mask, $i, $i, $i);
					}
					imagefilledrectangle($gdimg_rotate_mask, 0, 0, imagesx($gdimg_rotate_mask), imagesy($gdimg_rotate_mask), $color_mask[255]);
					$imageX = imagesx($gdimg_source);
					$imageY = imagesy($gdimg_source);
					for ($x = 0; $x < $imageX; $x++) {
						for ($y = 0; $y < $imageY; $y++) {
							$pixelcolor = phpthumb_functions::GetPixelColor($gdimg_source, $x, $y);
							imagesetpixel($gdimg_rotate_mask, $x, $y, $color_mask[255 - round($pixelcolor['alpha'] * 255 / 127)]);
						}
					}
					$gdimg_rotate_mask = imagerotate($gdimg_rotate_mask, $rotate_angle, $color_mask[0]);
					$gdimg_source      = imagerotate($gdimg_source,      $rotate_angle, $background_color);

					imagealphablending($gdimg_source, false);
					imagesavealpha($gdimg_source, true);
					//$this->is_alpha = true;
					$phpThumbFilters = new self();
					//$phpThumbFilters->phpThumbObject = $this;
					$phpThumbFilters->phpThumbObject = $phpThumbObject;
					$phpThumbFilters->ApplyMask($gdimg_rotate_mask, $gdimg_source);

					imagedestroy($gdimg_rotate_mask);

				} else {
					//$this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
				}

			} else {

				if (phpthumb_functions::gd_version() < 2) {
					//$this->DebugMessage('Using non-alpha rotate because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
				} elseif ($bg) {
					//$this->DebugMessage('Using non-alpha rotate because $this->bg is "'.$bg.'"', __FILE__, __LINE__);
				} elseif ($rotate_angle % 90) {
					//$this->DebugMessage('Using non-alpha rotate because ($rotate_angle % 90) = "'.($rotate_angle % 90).'"', __FILE__, __LINE__);
				} else {
					//$this->DebugMessage('Using non-alpha rotate because $this->thumbnailFormat is "'.$this->thumbnailFormat.'"', __FILE__, __LINE__);
				}

				if (imagecolortransparent($gdimg_source) >= 0) {
					// imagerotate() forgets all about an image's transparency and sets the transparent color to black
					// To compensate, flood-fill the transparent color of the source image with the specified background color first
					// then rotate and the colors should match

					if (!function_exists('imageistruecolor') || !imageistruecolor($gdimg_source)) {
						// convert paletted image to true-color before rotating to prevent nasty aliasing artifacts

						//$this->source_width  = imagesx($gdimg_source);
						//$this->source_height = imagesy($gdimg_source);
						$gdimg_newsrc = phpthumb_functions::ImageCreateFunction(imagesx($gdimg_source), imagesy($gdimg_source));
						$background_color = phpthumb_functions::ImageHexColorAllocate($gdimg_newsrc, $config_background_hexcolor);
						imagefilledrectangle($gdimg_newsrc, 0, 0, imagesx($gdimg_source), imagesy($gdimg_source), phpthumb_functions::ImageHexColorAllocate($gdimg_newsrc, $config_background_hexcolor));
						imagecopy($gdimg_newsrc, $gdimg_source, 0, 0, 0, 0, imagesx($gdimg_source), imagesy($gdimg_source));
						imagedestroy($gdimg_source);
						unset($gdimg_source);
						$gdimg_source = $gdimg_newsrc;
						unset($gdimg_newsrc);

					} else {

						imagecolorset(
							$gdimg_source,
							imagecolortransparent($gdimg_source),
							hexdec(substr($config_background_hexcolor, 0, 2)),
							hexdec(substr($config_background_hexcolor, 2, 2)),
							hexdec(substr($config_background_hexcolor, 4, 2)));

						imagecolortransparent($gdimg_source, -1);

					}
				}

				$gdimg_source = imagerotate($gdimg_source, $rotate_angle, $background_color);

			}
		}
		return true;
	}


	public function MeanRemoval(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_MEAN_REMOVAL)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_MEAN_REMOVAL)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::MeanRemoval($gdimg) [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	public function Negative(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_NEGATE)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_NEGATE)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		$ImageSX = imagesx($gdimg);
		$ImageSY = imagesy($gdimg);
		for ($x = 0; $x < $ImageSX; $x++) {
			for ($y = 0; $y < $ImageSY; $y++) {
				$currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, (~$currentPixel['red'] & 0xFF), (~$currentPixel['green'] & 0xFF), (~$currentPixel['blue'] & 0xFF), $currentPixel['alpha']);
				imagesetpixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	public function RoundedImageCorners(&$gdimg, $radius_x, $radius_y) {
		// generate mask at twice desired resolution and downsample afterwards for easy antialiasing
		// mask is generated as a white double-size ellipse on a triple-size black background and copy-paste-resampled
		// onto a correct-size mask image as 4 corners due to errors when the entire mask is resampled at once (gray edges)
		if ($gdimg_cornermask_triple = phpthumb_functions::ImageCreateFunction($radius_x * 6, $radius_y * 6)) {
			if ($gdimg_cornermask = phpthumb_functions::ImageCreateFunction(imagesx($gdimg), imagesy($gdimg))) {

				$color_transparent = imagecolorallocate($gdimg_cornermask_triple, 255, 255, 255);
				imagefilledellipse($gdimg_cornermask_triple, $radius_x * 3, $radius_y * 3, $radius_x * 4, $radius_y * 4, $color_transparent);

				imagefilledrectangle($gdimg_cornermask, 0, 0, imagesx($gdimg), imagesy($gdimg), $color_transparent);

				imagecopyresampled($gdimg_cornermask, $gdimg_cornermask_triple,                           0,                           0,     $radius_x,     $radius_y, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
				imagecopyresampled($gdimg_cornermask, $gdimg_cornermask_triple,                           0, imagesy($gdimg) - $radius_y,     $radius_x, $radius_y * 3, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
				imagecopyresampled($gdimg_cornermask, $gdimg_cornermask_triple, imagesx($gdimg) - $radius_x, imagesy($gdimg) - $radius_y, $radius_x * 3, $radius_y * 3, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
				imagecopyresampled($gdimg_cornermask, $gdimg_cornermask_triple, imagesx($gdimg) - $radius_x,                           0, $radius_x * 3,     $radius_y, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);

				self::ApplyMask($gdimg_cornermask, $gdimg);
				imagedestroy($gdimg_cornermask);
				$this->DebugMessage('RoundedImageCorners('.$radius_x.', '.$radius_y.') succeeded', __FILE__, __LINE__);
				return true;

			} else {
				$this->DebugMessage('FAILED: $gdimg_cornermask = phpthumb_functions::ImageCreateFunction('.imagesx($gdimg).', '.imagesy($gdimg).')', __FILE__, __LINE__);
			}
			imagedestroy($gdimg_cornermask_triple);

		} else {
			$this->DebugMessage('FAILED: $gdimg_cornermask_triple = phpthumb_functions::ImageCreateFunction('.($radius_x * 6).', '.($radius_y * 6).')', __FILE__, __LINE__);
		}
		return false;
	}


	public function Saturation(&$gdimg, $amount, $color='') {
		if ($amount == 0) {
			return true;
		} elseif ($amount > 0) {
			$amount = 0 - $amount;
		} else {
			$amount = abs($amount);
		}
		return self::Desaturate($gdimg, $amount, $color);
	}


	public function Sepia(&$gdimg, $amount, $targetColor) {
		$amount      = (is_numeric($amount) ? max(0, min(100, $amount)) : 50);
		$amountPct   = $amount / 100;
		$targetColor = (phpthumb_functions::IsHexColor($targetColor) ? $targetColor : 'A28065');

		if ($amount == 0) {
			return true;
		}

		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_GRAYSCALE)) {

				$r = round($amountPct * hexdec(substr($targetColor, 0, 2)));
				$g = round($amountPct * hexdec(substr($targetColor, 2, 2)));
				$b = round($amountPct * hexdec(substr($targetColor, 4, 2)));
				if (imagefilter($gdimg, IMG_FILTER_COLORIZE, $r, $g, $b)) {
					return true;
				}
				$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_COLORIZE)', __FILE__, __LINE__);
				// fall through and try it the hard way

			} else {

				$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_GRAYSCALE)', __FILE__, __LINE__);
				// fall through and try it the hard way

			}
		}

		$TargetPixel['red']   = hexdec(substr($targetColor, 0, 2));
		$TargetPixel['green'] = hexdec(substr($targetColor, 2, 2));
		$TargetPixel['blue']  = hexdec(substr($targetColor, 4, 2));

		$ImageSX = imagesx($gdimg);
		$ImageSY = imagesy($gdimg);
		for ($x = 0; $x < $ImageSX; $x++) {
			for ($y = 0; $y < $ImageSY; $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$GrayPixel = phpthumb_functions::GrayscalePixel($OriginalPixel);

				// http://www.gimpguru.org/Tutorials/SepiaToning/
				// "In the traditional sepia toning process, the tinting occurs most in
				// the mid-tones: the lighter and darker areas appear to be closer to B&W."
				$SepiaAmount = ((128 - abs($GrayPixel['red'] - 128)) / 128) * $amountPct;

				$NewPixel = array();
				foreach ($TargetPixel as $key => $value) {
					$NewPixel[$key] = round(max(0, min(255, $GrayPixel[$key] * (1 - $SepiaAmount) + ($TargetPixel[$key] * $SepiaAmount))));
				}
				$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue'], $OriginalPixel['alpha']);
				imagesetpixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	public function Smooth(&$gdimg, $amount=6) {
		$amount = min(25, max(0, $amount));
		if ($amount == 0) {
			return true;
		}
		if (phpthumb_functions::version_compare_replacement(PHP_VERSION, '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (imagefilter($gdimg, IMG_FILTER_SMOOTH, $amount)) {
				return true;
			}
			$this->DebugMessage('FAILED: imagefilter($gdimg, IMG_FILTER_SMOOTH, '.$amount.')', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::Smooth($gdimg, '.$amount.') [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	public function SourceTransparentColorMask(&$gdimg, $hexcolor, $min_limit=5, $max_limit=10) {
		$width  = imagesx($gdimg);
		$height = imagesy($gdimg);
		if ($gdimg_mask = imagecreatetruecolor($width, $height)) {
			$R = hexdec(substr($hexcolor, 0, 2));
			$G = hexdec(substr($hexcolor, 2, 2));
			$B = hexdec(substr($hexcolor, 4, 2));
			$targetPixel = array('red'=>$R, 'green'=>$G, 'blue'=>$B);
			$cutoffRange = $max_limit - $min_limit;
			for ($x = 0; $x < $width; $x++) {
				for ($y = 0; $y < $height; $y++) {
					$currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
					$colorDiff = phpthumb_functions::PixelColorDifferencePercent($currentPixel, $targetPixel);
					$grayLevel = min($cutoffRange, max(0, -$min_limit + $colorDiff)) * (255 / max(1, $cutoffRange));
					$newColor = imagecolorallocate($gdimg_mask, $grayLevel, $grayLevel, $grayLevel);
					imagesetpixel($gdimg_mask, $x, $y, $newColor);
				}
			}
			return $gdimg_mask;
		}
		return false;
	}


	public function Threshold(&$gdimg, $cutoff) {
		$width  = imagesx($gdimg);
		$height = imagesy($gdimg);
		$cutoff = min(255, max(0, ($cutoff ? $cutoff : 128)));
		for ($x = 0; $x < $width; $x++) {
			for ($y = 0; $y < $height; $y++) {
				$currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$grayPixel = phpthumb_functions::GrayscalePixel($currentPixel);
				if ($grayPixel['red'] < $cutoff) {
					$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 0x00, 0x00, 0x00, $currentPixel['alpha']);
				} else {
					$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 0xFF, 0xFF, 0xFF, $currentPixel['alpha']);
				}
				imagesetpixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	public function ImageTrueColorToPalette2(&$image, $dither, $ncolors) {
		// http://www.php.net/manual/en/function.imagetruecolortopalette.php
		// zmorris at zsculpt dot com (17-Aug-2004 06:58)
		$width  = imagesx($image);
		$height = imagesy($image);
		$image_copy = imagecreatetruecolor($width, $height);
		//imagecopymerge($image_copy, $image, 0, 0, 0, 0, $width, $height, 100);
		imagecopy($image_copy, $image, 0, 0, 0, 0, $width, $height);
		imagetruecolortopalette($image, $dither, $ncolors);
		imagecolormatch($image_copy, $image);
		imagedestroy($image_copy);
		return true;
	}

	public function ReduceColorDepth(&$gdimg, $colors=256, $dither=true) {
		$colors = max(min($colors, 256), 2);
		// imagetruecolortopalette usually makes ugly colors, the replacement is a bit better
		//imagetruecolortopalette($gdimg, $dither, $colors);
		self::ImageTrueColorToPalette2($gdimg, $dither, $colors);
		return true;
	}


	public function WhiteBalance(&$gdimg, $targetColor='') {
		if (phpthumb_functions::IsHexColor($targetColor)) {
			$targetPixel = array(
				'red'   => hexdec(substr($targetColor, 0, 2)),
				'green' => hexdec(substr($targetColor, 2, 2)),
				'blue'  => hexdec(substr($targetColor, 4, 2))
			);
		} else {
			$Analysis = self::HistogramAnalysis($gdimg, false);
			$targetPixel = array(
				'red'   => max(array_keys($Analysis['red'])),
				'green' => max(array_keys($Analysis['green'])),
				'blue'  => max(array_keys($Analysis['blue']))
			);
		}
		$grayValue = phpthumb_functions::GrayscaleValue($targetPixel['red'], $targetPixel['green'], $targetPixel['blue']);
		$scaleR = $grayValue / $targetPixel['red'];
		$scaleG = $grayValue / $targetPixel['green'];
		$scaleB = $grayValue / $targetPixel['blue'];

		for ($x = 0, $xMax = imagesx($gdimg); $x < $xMax; $x++) {
			for ($y = 0, $yMax = imagesy($gdimg); $y < $yMax; $y++) {
				$currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe(
					$gdimg,
					max(0, min(255, round($currentPixel['red']   * $scaleR))),
					max(0, min(255, round($currentPixel['green'] * $scaleG))),
					max(0, min(255, round($currentPixel['blue']  * $scaleB))),
					$currentPixel['alpha']
				);
				imagesetpixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	public function WatermarkText(&$gdimg, $text, $size, $alignment, $hex_color='000000', $ttffont='', $opacity=100, $margin=5, $angle=0, $bg_color=false, $bg_opacity=0, $fillextend='', $lineheight=1.0) {
		// text watermark requested
		if (!$text) {
			return false;
		}
		imagealphablending($gdimg, true);

		if (preg_match('#^([0-9\\.\\-]*)x([0-9\\.\\-]*)(@[LCR])?$#i', $alignment, $matches)) {
			$originOffsetX = (int) $matches[ 1];
			$originOffsetY = (int) $matches[ 2];
			$alignment = (@$matches[4] ? $matches[4] : 'L');
			$margin = 0;
		} else {
			$originOffsetX = 0;
			$originOffsetY = 0;
		}
		$lineheight = min(100.0, max(0.01, (float) $lineheight));

		$metaTextArray = array(
			'^Fb' =>       $this->phpThumbObject->getimagesizeinfo['filesize'],
			'^Fk' => round($this->phpThumbObject->getimagesizeinfo['filesize'] / 1024),
			'^Fm' => round($this->phpThumbObject->getimagesizeinfo['filesize'] / 1048576),
			'^X'  => $this->phpThumbObject->getimagesizeinfo[0],
			'^Y'  => $this->phpThumbObject->getimagesizeinfo[1],
			'^x'  => imagesx($gdimg),
			'^y'  => imagesy($gdimg),
			'^^'  => '^',
		);
		$text = strtr($text, $metaTextArray);

		$text = str_replace(array(
			"\r\n",
			"\r"
		), "\n", $text);
		$textlines = explode("\n", $text);
		$this->DebugMessage('Processing '.count($textlines).' lines of text', __FILE__, __LINE__);

		if (@is_readable($ttffont) && is_file($ttffont)) {

			$opacity = 100 - (int) max(min($opacity, 100), 0);
			$letter_color_text = phpthumb_functions::ImageHexColorAllocate($gdimg, $hex_color, false, $opacity * 1.27);

			$this->DebugMessage('Using TTF font "'.$ttffont.'"', __FILE__, __LINE__);

			$TTFbox = imagettfbbox($size, $angle, $ttffont, $text);

			$min_x = min($TTFbox[0], $TTFbox[2], $TTFbox[4], $TTFbox[6]);
			$max_x = max($TTFbox[0], $TTFbox[2], $TTFbox[4], $TTFbox[6]);
			//$text_width = round($max_x - $min_x + ($size * 0.5));
			$text_width = round($max_x - $min_x);

			$min_y = min($TTFbox[1], $TTFbox[3], $TTFbox[5], $TTFbox[7]);
			$max_y = max($TTFbox[1], $TTFbox[3], $TTFbox[5], $TTFbox[7]);
			//$text_height = round($max_y - $min_y + ($size * 0.5));
			$text_height = round($max_y - $min_y);

			$TTFboxChar = imagettfbbox($size, $angle, $ttffont, 'jH');
			$char_min_y = min($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
			$char_max_y = max($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
			$char_height = round($char_max_y - $char_min_y);

			if ($alignment == '*') {

				$text_origin_y = $char_height + $margin;
				while (($text_origin_y - $text_height) < imagesy($gdimg)) {
					$text_origin_x = $margin;
					while ($text_origin_x < imagesx($gdimg)) {
						imagettftext($gdimg, $size, $angle, $text_origin_x, $text_origin_y, $letter_color_text, $ttffont, $text);
						$text_origin_x += ($text_width + $margin);
					}
					$text_origin_y += ($text_height + $margin) * $lineheight;
				}

			} else {

				// this block for background color only

				$text_origin_x = 0;
				$text_origin_y = 0;
				switch ($alignment) {
					case '*':
						// handled separately
						break;

					case 'T':
						$text_origin_x = ($originOffsetX ? $originOffsetX - round($text_width / 2) : round((imagesx($gdimg) - $text_width) / 2));
						$text_origin_y = $char_height + $margin + $originOffsetY;
						break;

					case 'B':
						$text_origin_x = ($originOffsetX ? $originOffsetX - round($text_width / 2) : round((imagesx($gdimg) - $text_width) / 2));
						$text_origin_y = imagesy($gdimg) + $TTFbox[1] - $margin + $originOffsetY;
						break;

					case 'L':
						$text_origin_x = $margin + $originOffsetX;
						$text_origin_y = ($originOffsetY ? $originOffsetY : round((imagesy($gdimg) - $text_height) / 2) + $char_height);
						break;

					case 'R':
						$text_origin_x = ($originOffsetX ? $originOffsetX - $text_width : imagesx($gdimg) - $text_width  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin);
						$text_origin_y = ($originOffsetY ? $originOffsetY : round((imagesy($gdimg) - $text_height) / 2) + $char_height);
						break;

					case 'C':
						$text_origin_x = ($originOffsetX ? $originOffsetX - round($text_width / 2) : round((imagesx($gdimg) - $text_width) / 2));
						$text_origin_y = ($originOffsetY ? $originOffsetY : round((imagesy($gdimg) - $text_height) / 2) + $char_height);
						break;

					case 'TL':
						$text_origin_x = $margin + $originOffsetX;
						$text_origin_y = $char_height + $margin + $originOffsetY;
						break;

					case 'TR':
						$text_origin_x = ($originOffsetX ? $originOffsetX - $text_width : imagesx($gdimg) - $text_width  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin);
						$text_origin_y = $char_height + $margin + $originOffsetY;
						break;

					case 'BL':
						$text_origin_x = $margin + $originOffsetX;
						$text_origin_y = imagesy($gdimg) + $TTFbox[1] - $margin + $originOffsetY;
						break;

					case 'BR':
					default:
						$text_origin_x = ($originOffsetX ? $originOffsetX - $text_width : imagesx($gdimg) - $text_width  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin);
						$text_origin_y = imagesy($gdimg) + $TTFbox[1] - $margin + $originOffsetY;
						break;
				}

				if (phpthumb_functions::IsHexColor($bg_color)) {
					$text_background_alpha = round(127 * ((100 - min(max(0, $bg_opacity), 100)) / 100));
					$text_color_background = phpthumb_functions::ImageHexColorAllocate($gdimg, $bg_color, false, $text_background_alpha);
				} else {
					$text_color_background = phpthumb_functions::ImageHexColorAllocate($gdimg, 'FFFFFF', false, 127);
				}
				$x1 = $text_origin_x + $min_x;
				$y1 = $text_origin_y + $TTFbox[1];
				$x2 = $text_origin_x + $min_x + $text_width;
				$y2 = $text_origin_y + $TTFbox[1] - $text_height;
				$x_TL = false !== stripos($fillextend, "x") ?               0 : min($x1, $x2);
				$y_TL = false !== stripos($fillextend, "y") ?               0 : min($y1, $y2);
				$x_BR = false !== stripos($fillextend, "x") ? imagesx($gdimg) : max($x1, $x2);
				$y_BR = false !== stripos($fillextend, "y") ? imagesy($gdimg) : max($y1, $y2);
				$this->DebugMessage('WatermarkText() calling imagefilledrectangle($gdimg, '.$x_TL.', '.$y_TL.', '.$x_BR.', '.$y_BR.', $text_color_background)', __FILE__, __LINE__);
				imagefilledrectangle($gdimg, $x_TL, $y_TL, $x_BR, $y_BR, $text_color_background);

				// end block for background color only


				$y_offset = 0;
				foreach ($textlines as $dummy => $line) {

					$TTFboxLine = imagettfbbox($size, $angle, $ttffont, $line);
					$min_x_line = min($TTFboxLine[0], $TTFboxLine[2], $TTFboxLine[4], $TTFboxLine[6]);
					$max_x_line = max($TTFboxLine[0], $TTFboxLine[2], $TTFboxLine[4], $TTFboxLine[6]);
					$text_width_line = round($max_x_line - $min_x_line);

					switch ($alignment) {
						// $text_origin_y set above, just re-set $text_origin_x here as needed

						case 'L':
						case 'TL':
						case 'BL':
							// no change necessary
							break;

						case 'C':
						case 'T':
						case 'B':
							$text_origin_x = ($originOffsetX ? $originOffsetX - round($text_width_line / 2) : round((imagesx($gdimg) - $text_width_line) / 2));
							break;

						case 'R':
						case 'TR':
						case 'BR':
							$text_origin_x = ($originOffsetX ? $originOffsetX - $text_width_line : imagesx($gdimg) - $text_width_line  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin);
							break;
					}

					//imagettftext($gdimg, $size, $angle, $text_origin_x, $text_origin_y, $letter_color_text, $ttffont, $text);
					$this->DebugMessage('WatermarkText() calling imagettftext($gdimg, '.$size.', '.$angle.', '.$text_origin_x.', '.($text_origin_y + $y_offset).', $letter_color_text, '.$ttffont.', '.$line.')', __FILE__, __LINE__);
					imagettftext($gdimg, $size, $angle, $text_origin_x, $text_origin_y + $y_offset, $letter_color_text, $ttffont, $line);

					$y_offset += $char_height * $lineheight;
				}

			}
			return true;

		} else {

			$size = min(5, max(1, $size));
			$this->DebugMessage('Using built-in font (size='.$size.') for text watermark'.($ttffont ? ' because $ttffont !is_readable('.$ttffont.')' : ''), __FILE__, __LINE__);

			$text_width  = 0;
			$text_height = 0;
			foreach ($textlines as $dummy => $line) {
				$text_width   = max($text_width, imagefontwidth($size) * strlen($line));
				$text_height += imagefontheight($size);
			}
			if ($img_watermark = phpthumb_functions::ImageCreateFunction($text_width, $text_height)) {
				imagealphablending($img_watermark, false);
				if (phpthumb_functions::IsHexColor($bg_color)) {
					$text_background_alpha = round(127 * ((100 - min(max(0, $bg_opacity), 100)) / 100));
					$text_color_background = phpthumb_functions::ImageHexColorAllocate($img_watermark, $bg_color, false, $text_background_alpha);
				} else {
					$text_color_background = phpthumb_functions::ImageHexColorAllocate($img_watermark, 'FFFFFF', false, 127);
				}
				$this->DebugMessage('WatermarkText() calling imagefilledrectangle($img_watermark, 0, 0, '.imagesx($img_watermark).', '.imagesy($img_watermark).', $text_color_background)', __FILE__, __LINE__);
				imagefilledrectangle($img_watermark, 0, 0, imagesx($img_watermark), imagesy($img_watermark), $text_color_background);

				$img_watermark_mask    = false;
				$mask_color_background = false;
				$mask_color_watermark  = false;
				if ($angle && function_exists('imagerotate')) {
					// using $img_watermark_mask is pointless if imagerotate function isn't available
					if ($img_watermark_mask = phpthumb_functions::ImageCreateFunction($text_width, $text_height)) {
						$mask_color_background = imagecolorallocate($img_watermark_mask, 0, 0, 0);
						imagealphablending($img_watermark_mask, false);
						imagefilledrectangle($img_watermark_mask, 0, 0, imagesx($img_watermark_mask), imagesy($img_watermark_mask), $mask_color_background);
						$mask_color_watermark = imagecolorallocate($img_watermark_mask, 255, 255, 255);
					}
				}

				$text_color_watermark = phpthumb_functions::ImageHexColorAllocate($img_watermark, $hex_color);
				$x_offset = 0;
				foreach ($textlines as $key => $line) {
					switch ($alignment) {
						case 'C':
							$x_offset = round(($text_width - (imagefontwidth($size) * strlen($line))) / 2);
							$originOffsetX = (imagesx($gdimg) - imagesx($img_watermark)) / 2;
							$originOffsetY = (imagesy($gdimg) - imagesy($img_watermark)) / 2;
							break;

						case 'T':
							$x_offset = round(($text_width - (imagefontwidth($size) * strlen($line))) / 2);
							$originOffsetX = (imagesx($gdimg) - imagesx($img_watermark)) / 2;
							$originOffsetY = $margin;
							break;

						case 'B':
							$x_offset = round(($text_width - (imagefontwidth($size) * strlen($line))) / 2);
							$originOffsetX = (imagesx($gdimg) - imagesx($img_watermark)) / 2;
							$originOffsetY = imagesy($gdimg) - imagesy($img_watermark) - $margin;
							break;

						case 'L':
							$x_offset = 0;
							$originOffsetX = $margin;
							$originOffsetY = (imagesy($gdimg) - imagesy($img_watermark)) / 2;
							break;

						case 'TL':
							$x_offset = 0;
							$originOffsetX = $margin;
							$originOffsetY = $margin;
							break;

						case 'BL':
							$x_offset = 0;
							$originOffsetX = $margin;
							$originOffsetY = imagesy($gdimg) - imagesy($img_watermark) - $margin;
							break;

						case 'R':
							$x_offset = $text_width - (imagefontwidth($size) * strlen($line));
							$originOffsetX = imagesx($gdimg) - imagesx($img_watermark) - $margin;
							$originOffsetY = (imagesy($gdimg) - imagesy($img_watermark)) / 2;
							break;

						case 'TR':
							$x_offset = $text_width - (imagefontwidth($size) * strlen($line));
							$originOffsetX = imagesx($gdimg) - imagesx($img_watermark) - $margin;
							$originOffsetY = $margin;
							break;

						case 'BR':
						default:
							if (!empty($originOffsetX) || !empty($originOffsetY)) {
								// absolute pixel positioning
							} else {
								$x_offset = $text_width - (imagefontwidth($size) * strlen($line));
								$originOffsetX = imagesx($gdimg) - imagesx($img_watermark) - $margin;
								$originOffsetY = imagesy($gdimg) - imagesy($img_watermark) - $margin;
							}
							break;
					}
					$this->DebugMessage('WatermarkText() calling imagestring($img_watermark, '.$size.', '.$x_offset.', '.($key * imagefontheight($size)).', '.$line.', $text_color_watermark)', __FILE__, __LINE__);
					imagestring($img_watermark, $size, $x_offset, $key * imagefontheight($size), $line, $text_color_watermark);
					if ($angle && $img_watermark_mask) {
						$this->DebugMessage('WatermarkText() calling imagestring($img_watermark_mask, '.$size.', '.$x_offset.', '.($key * imagefontheight($size) * $lineheight).', '.$text.', $mask_color_watermark)', __FILE__, __LINE__);
						imagestring($img_watermark_mask, $size, $x_offset, $key * imagefontheight($size) * $lineheight, $text, $mask_color_watermark);
					}
				}
				if ($angle && $img_watermark_mask) {
					$img_watermark      = imagerotate($img_watermark,      $angle, $text_color_background);
					$img_watermark_mask = imagerotate($img_watermark_mask, $angle, $mask_color_background);
					self::ApplyMask($img_watermark_mask, $img_watermark);
				}
				//phpthumb_filters::WatermarkOverlay($gdimg, $img_watermark, $alignment, $opacity, $margin);
				$this->DebugMessage('WatermarkText() calling phpthumb_filters::WatermarkOverlay($gdimg, $img_watermark, '.($originOffsetX.'x'.$originOffsetY).', '.$opacity.', 0)', __FILE__, __LINE__);
				self::WatermarkOverlay($gdimg, $img_watermark, $originOffsetX.'x'.$originOffsetY, $opacity, 0);
				imagedestroy($img_watermark);
				return true;
			}

		}
		return false;
	}


	public function WatermarkOverlay(&$gdimg_dest, &$img_watermark, $alignment='*', $opacity=50, $margin_x=5, $margin_y=null) {

		if (is_resource($gdimg_dest) && is_resource($img_watermark)) {
			$img_source_width          = imagesx($gdimg_dest);
			$img_source_height         = imagesy($gdimg_dest);
			$watermark_source_width    = imagesx($img_watermark);
			$watermark_source_height   = imagesy($img_watermark);
			$watermark_opacity_percent = max(0, min(100, $opacity));
			$margin_y = (null === $margin_y ? $margin_x : $margin_y);
			$watermark_margin_x = ((($margin_x > 0) && ($margin_x < 1)) ? round((1 - $margin_x) * $img_source_width)  : $margin_x);
			$watermark_margin_y = ((($margin_y > 0) && ($margin_y < 1)) ? round((1 - $margin_y) * $img_source_height) : $margin_y);
			$watermark_destination_x = 0;
			$watermark_destination_y = 0;
			if (preg_match('#^([0-9\\.\\-]*)x([0-9\\.\\-]*)$#i', $alignment, $matches)) {
				$watermark_destination_x = (int) $matches[ 1];
				$watermark_destination_y = (int) $matches[ 2];
			} else {
				switch ($alignment) {
					case '*':
						if ($gdimg_tiledwatermark = phpthumb_functions::ImageCreateFunction($img_source_width, $img_source_height)) {

							imagealphablending($gdimg_tiledwatermark, false);
							imagesavealpha($gdimg_tiledwatermark, true);
							$text_color_transparent = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_tiledwatermark, 255, 0, 255, 127);
							imagefill($gdimg_tiledwatermark, 0, 0, $text_color_transparent);

							// set the tiled image transparent color to whatever the untiled image transparency index is
	//						imagecolortransparent($gdimg_tiledwatermark, imagecolortransparent($img_watermark));

							// a "cleaner" way of doing it, but can't handle the margin feature :(
	//						imagesettile($gdimg_tiledwatermark, $img_watermark);
	//						imagefill($gdimg_tiledwatermark, 0, 0, IMG_COLOR_TILED);
	//						break;

	//						imagefill($gdimg_tiledwatermark, 0, 0, imagecolortransparent($gdimg_tiledwatermark));
							// tile the image as many times as can fit
							for ($x = $watermark_margin_x; $x < ($img_source_width + $watermark_source_width); $x += ($watermark_source_width + $watermark_margin_x)) {
								for ($y = $watermark_margin_y; $y < ($img_source_height + $watermark_source_height); $y += ($watermark_source_height + $watermark_margin_y)) {
									imagecopy(
										$gdimg_tiledwatermark,
										$img_watermark,
										$x,
										$y,
										0,
										0,
										min($watermark_source_width,  $img_source_width  - $x - $watermark_margin_x),
										min($watermark_source_height, $img_source_height - $y - $watermark_margin_y)
									);
								}
							}

							$watermark_source_width  = imagesx($gdimg_tiledwatermark);
							$watermark_source_height = imagesy($gdimg_tiledwatermark);
							$watermark_destination_x = 0;
							$watermark_destination_y = 0;

							imagedestroy($img_watermark);
							$img_watermark = $gdimg_tiledwatermark;
						}
						break;

					case 'T':
						$watermark_destination_x = round((($img_source_width  / 2) - ($watermark_source_width / 2)) + $watermark_margin_x);
						$watermark_destination_y = $watermark_margin_y;
						break;

					case 'B':
						$watermark_destination_x = round((($img_source_width  / 2) - ($watermark_source_width / 2)) + $watermark_margin_x);
						$watermark_destination_y = $img_source_height - $watermark_source_height - $watermark_margin_y;
						break;

					case 'L':
						$watermark_destination_x = $watermark_margin_x;
						$watermark_destination_y = round((($img_source_height / 2) - ($watermark_source_height / 2)) + $watermark_margin_y);
						break;

					case 'R':
						$watermark_destination_x = $img_source_width - $watermark_source_width - $watermark_margin_x;
						$watermark_destination_y = round((($img_source_height / 2) - ($watermark_source_height / 2)) + $watermark_margin_y);
						break;

					case 'C':
						$watermark_destination_x = round(($img_source_width  / 2) - ($watermark_source_width  / 2));
						$watermark_destination_y = round(($img_source_height / 2) - ($watermark_source_height / 2));
						break;

					case 'TL':
						$watermark_destination_x = $watermark_margin_x;
						$watermark_destination_y = $watermark_margin_y;
						break;

					case 'TR':
						$watermark_destination_x = $img_source_width - $watermark_source_width - $watermark_margin_x;
						$watermark_destination_y = $watermark_margin_y;
						break;

					case 'BL':
						$watermark_destination_x = $watermark_margin_x;
						$watermark_destination_y = $img_source_height - $watermark_source_height - $watermark_margin_y;
						break;

					case 'BR':
					default:
						$watermark_destination_x = $img_source_width  - $watermark_source_width  - $watermark_margin_x;
						$watermark_destination_y = $img_source_height - $watermark_source_height - $watermark_margin_y;
						break;
				}
			}
			imagealphablending($gdimg_dest, false);
			imagesavealpha($gdimg_dest, true);
			imagesavealpha($img_watermark, true);
			phpthumb_functions::ImageCopyRespectAlpha($gdimg_dest, $img_watermark, $watermark_destination_x, $watermark_destination_y, 0, 0, $watermark_source_width, $watermark_source_height, $watermark_opacity_percent);

			return true;
		}
		return false;
	}

}
