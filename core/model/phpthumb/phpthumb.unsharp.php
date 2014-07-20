<?php
//////////////////////////////////////////////////////////////
////
////            Unsharp Mask for PHP - version 2.1.1
////
////    Unsharp mask algorithm by Torstein Hønsi 2003-07.
////             thoensi_at_netcom_dot_no.
////               Please leave this notice.
////
//////////////////////////////////////////////////////////////
//  From: http://vikjavev.no/computing/ump.php              //
//                                                          //
//  Reformatted by James Heinrich <info@silisoftware.com>   //
//    for use in phpThumb() on 3 February 2003.             //
//    updated to v2.1.1 on 24 April 2011                    //
//                                                          //
//  phpThumb() is found at http://phpthumb.sourceforge.net  //
//         and/or https://github.com/JamesHeinrich/phpThumb //
//////////////////////////////////////////////////////////////

/*
New:
- In version 2.1 (February 26 2007) Tom Bishop has done some important speed enhancements.
- From version 2 (July 17 2006) the script uses the imageconvolution function in PHP
  version >= 5.1, which improves the performance considerably.

Unsharp masking is a traditional darkroom technique that has proven very suitable for
digital imaging. The principle of unsharp masking is to create a blurred copy of the image
and compare it to the underlying original. The difference in colour values
between the two images is greatest for the pixels near sharp edges. When this
difference is subtracted from the original image, the edges will be
accentuated.

The Amount parameter simply says how much of the effect you want. 100 is 'normal'.
Radius is the radius of the blurring circle of the mask. 'Threshold' is the least
difference in colour values that is allowed between the original and the mask. In practice
this means that low-contrast areas of the picture are left unrendered whereas edges
are treated normally. This is good for pictures of e.g. skin or blue skies.

Any suggenstions for improvement of the algorithm, expecially regarding the speed
and the roundoff errors in the Gaussian blur process, are welcome.
*/

class phpUnsharpMask {

	static function applyUnsharpMask(&$img, $amount, $radius, $threshold) {

		// $img is an image that is already created within php using
		// imgcreatetruecolor. No url! $img must be a truecolor image.

		// Attempt to calibrate the parameters to Photoshop:
		$amount = min($amount, 500) * 0.016;
		$radius = abs(round(min(50, $radius) * 2)); // Only integers make sense.
		$threshold = min(255, $threshold);
		if ($radius == 0) {
			return true;
		}
		$w = ImageSX($img);
		$h = ImageSY($img);
		$imgCanvas = ImageCreateTrueColor($w, $h);
		$imgBlur   = ImageCreateTrueColor($w, $h);

		// Gaussian blur matrix:
		//
		//    1    2    1
		//    2    4    2
		//    1    2    1
		//
		//////////////////////////////////////////////////

		if (function_exists('imageconvolution')) { // PHP >= 5.1
			$matrix = array(
				array(1, 2, 1),
				array(2, 4, 2),
				array(1, 2, 1)
			);
			ImageCopy($imgBlur, $img, 0, 0, 0, 0, $w, $h);
			ImageConvolution($imgBlur, $matrix, 16, 0);

		} else {

			// Move copies of the image around one pixel at the time and merge them with weight
			// according to the matrix. The same matrix is simply repeated for higher radii.
			for ($i = 0; $i < $radius; $i++)    {
				ImageCopy(     $imgBlur,   $img,       0, 0, 1, 0, $w - 1, $h);               // left
				ImageCopyMerge($imgBlur,   $img,       1, 0, 0, 0, $w    , $h,     50);       // right
				ImageCopyMerge($imgBlur,   $img,       0, 0, 0, 0, $w    , $h,     50);       // center
				ImageCopy(     $imgCanvas, $imgBlur,   0, 0, 0, 0, $w    , $h);
				ImageCopyMerge($imgBlur,   $imgCanvas, 0, 0, 0, 1, $w    , $h - 1, 33.33333); // up
				ImageCopyMerge($imgBlur,   $imgCanvas, 0, 1, 0, 0, $w    , $h,     25);       // down
			}
		}

		if ($threshold > 0){
			// Calculate the difference between the blurred pixels and the original
			// and set the pixels
			for ($x = 0; $x < $w-1; $x++)    { // each row
				for ($y = 0; $y < $h; $y++)    { // each pixel

					$rgbOrig = ImageColorAt($img, $x, $y);
					$rOrig = (($rgbOrig >> 16) & 0xFF);
					$gOrig = (($rgbOrig >>  8) & 0xFF);
					$bOrig =  ($rgbOrig        & 0xFF);

					$rgbBlur = ImageColorAt($imgBlur, $x, $y);

					$rBlur = (($rgbBlur >> 16) & 0xFF);
					$gBlur = (($rgbBlur >>  8) & 0xFF);
					$bBlur =  ($rgbBlur        & 0xFF);

					// When the masked pixels differ less from the original
					// than the threshold specifies, they are set to their original value.
					$rNew = ((abs($rOrig - $rBlur) >= $threshold) ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig)) : $rOrig);
					$gNew = ((abs($gOrig - $gBlur) >= $threshold) ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig)) : $gOrig);
					$bNew = ((abs($bOrig - $bBlur) >= $threshold) ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig)) : $bOrig);

					if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
						$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
						ImageSetPixel($img, $x, $y, $pixCol);
					}
				}
			}
		} else {
			for ($x = 0; $x < $w; $x++)    { // each row
				for ($y = 0; $y < $h; $y++)    { // each pixel
					$rgbOrig = ImageColorAt($img, $x, $y);
					$rOrig = (($rgbOrig >> 16) & 0xFF);
					$gOrig = (($rgbOrig >>  8) & 0xFF);
					$bOrig =  ($rgbOrig        & 0xFF);

					$rgbBlur = ImageColorAt($imgBlur, $x, $y);

					$rBlur = (($rgbBlur >> 16) & 0xFF);
					$gBlur = (($rgbBlur >>  8) & 0xFF);
					$bBlur =  ($rgbBlur        & 0xFF);

					$rNew = min(255, max(0, ($amount * ($rOrig - $rBlur)) + $rOrig));
					$gNew = min(255, max(0, ($amount * ($gOrig - $gBlur)) + $gOrig));
					$bNew = min(255, max(0, ($amount * ($bOrig - $bBlur)) + $bOrig));
					$rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew;
					ImageSetPixel($img, $x, $y, $rgbNew);
				}
			}
		}
		ImageDestroy($imgCanvas);
		ImageDestroy($imgBlur);
		return true;
	}
}
?>