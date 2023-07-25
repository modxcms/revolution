<?php
//////////////////////////////////////////////////////////////
//   phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net      //
//         and/or https://github.com/JamesHeinrich/phpThumb //
//////////////////////////////////////////////////////////////
///                                                         //
// phpthumb.functions.php - general support functions       //
//                                                         ///
//////////////////////////////////////////////////////////////

class phpthumb_functions {

	public static function is_windows() {
		return (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
	}

	public static function user_function_exists($functionname) {
		if (function_exists('get_defined_functions')) {
			static $get_defined_functions = array();
			if (empty($get_defined_functions)) {
				$get_defined_functions = get_defined_functions();
			}
			return in_array(strtolower($functionname), $get_defined_functions['user']);
		}
		return function_exists($functionname);
	}


	public static function builtin_function_exists($functionname) {
		if (function_exists('get_defined_functions')) {
			static $get_defined_functions = array();
			if (empty($get_defined_functions)) {
				$get_defined_functions = get_defined_functions();
			}
			return in_array(strtolower($functionname), $get_defined_functions['internal']);
		}
		return function_exists($functionname);
	}


	public static function version_compare_replacement_sub($version1, $version2, $operator='') {
		// If you specify the third optional operator argument, you can test for a particular relationship.
		// The possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively.
		// Using this argument, the function will return 1 if the relationship is the one specified by the operator, 0 otherwise.

		// If a part contains special version strings these are handled in the following order:
		// (any string not found in this list) < (dev) < (alpha = a) < (beta = b) < (RC = rc) < (#) < (pl = p)
		static $versiontype_lookup = array();
		if (empty($versiontype_lookup)) {
			$versiontype_lookup['dev']   = 10001;
			$versiontype_lookup['a']     = 10002;
			$versiontype_lookup['alpha'] = 10002;
			$versiontype_lookup['b']     = 10003;
			$versiontype_lookup['beta']  = 10003;
			$versiontype_lookup['RC']    = 10004;
			$versiontype_lookup['rc']    = 10004;
			$versiontype_lookup['#']     = 10005;
			$versiontype_lookup['pl']    = 10006;
			$versiontype_lookup['p']     = 10006;
		}
		$version1 = (isset($versiontype_lookup[$version1]) ? $versiontype_lookup[$version1] : $version1);
		$version2 = (isset($versiontype_lookup[$version2]) ? $versiontype_lookup[$version2] : $version2);

		switch ($operator) {
			case '<':
			case 'lt':
				return (int) ($version1 < $version2);
				break;
			case '<=':
			case 'le':
				return (int) ($version1 <= $version2);
				break;
			case '>':
			case 'gt':
				return (int) ($version1 > $version2);
				break;
			case '>=':
			case 'ge':
				return (int) ($version1 >= $version2);
				break;
			case '==':
			case '=':
			case 'eq':
				return (int) ($version1 == $version2);
				break;
			case '!=':
			case '<>':
			case 'ne':
				return (int) ($version1 != $version2);
				break;
		}
		if ($version1 == $version2) {
			return 0;
		} elseif ($version1 < $version2) {
			return -1;
		}
		return 1;
	}


	public static function version_compare_replacement($version1, $version2, $operator='') {
		if (function_exists('version_compare')) {
			// built into PHP v4.1.0+
			return version_compare($version1, $version2, $operator);
		}

		// The function first replaces _, - and + with a dot . in the version strings
		$version1 = strtr($version1, '_-+', '...');
		$version2 = strtr($version2, '_-+', '...');

		// and also inserts dots . before and after any non number so that for example '4.3.2RC1' becomes '4.3.2.RC.1'.
		// Then it splits the results like if you were using explode('.',$ver). Then it compares the parts starting from left to right.
		$version1 = preg_replace('#([\d]+)([A-Z]+)([\d]+)#i', '$1.$2.$3', $version1);
		$version2 = preg_replace('#([\d]+)([A-Z]+)([\d]+)#i', '$1.$2.$3', $version2);

		$parts1 = explode('.', $version1);
		$parts2 = explode('.', $version1);
		$parts_count = max(count($parts1), count($parts2));
		for ($i = 0; $i < $parts_count; $i++) {
			$comparison = self::version_compare_replacement_sub($version1, $version2, $operator);
			if ($comparison != 0) {
				return $comparison;
			}
		}
		return 0;
	}

	public static function escapeshellarg_replacement($arg) {
		if (function_exists('escapeshellarg') && !self::FunctionIsDisabled('escapeshellarg')) {
			return escapeshellarg($arg);
		}
		return '\''.str_replace('\'', '\\\'', $arg).'\'';
	}

	public static function phpinfo_array() {
		static $phpinfo_array = array();
		if (empty($phpinfo_array)) {
			ob_start();
			phpinfo();
			$phpinfo = ob_get_contents();
			ob_end_clean();
			$phpinfo_array = explode("\n", $phpinfo);
		}
		return $phpinfo_array;
	}


	public static function exif_info() {
		static $exif_info = array();
		if (empty($exif_info)) {
			// based on code by johnschaefer at gmx dot de
			// from PHP help on gd_info()
			$exif_info = array(
				'EXIF Support'           => '',
				'EXIF Version'           => '',
				'Supported EXIF Version' => '',
				'Supported filetypes'    => ''
			);
			$phpinfo_array = self::phpinfo_array();
			foreach ($phpinfo_array as $line) {
				$line = trim(strip_tags($line));
				foreach ($exif_info as $key => $value) {
					if (strpos($line, $key) === 0) {
						$newvalue = trim(str_replace($key, '', $line));
						$exif_info[$key] = $newvalue;
					}
				}
			}
		}
		return $exif_info;
	}


	public static function ImageTypeToMIMEtype($imagetype) {
		if (function_exists('image_type_to_mime_type') && ($imagetype >= 1) && ($imagetype <= 19)) {
			// PHP v4.3.0+
			return image_type_to_mime_type($imagetype);
		}
		static $image_type_to_mime_type = array(
			1  => 'image/gif',                     // IMAGETYPE_GIF
			2  => 'image/jpeg',                    // IMAGETYPE_JPEG
			3  => 'image/png',                     // IMAGETYPE_PNG
			4  => 'application/x-shockwave-flash', // IMAGETYPE_SWF
			5  => 'image/psd',                     // IMAGETYPE_PSD
			6  => 'image/bmp',                     // IMAGETYPE_BMP
			7  => 'image/tiff',                    // IMAGETYPE_TIFF_II (intel byte order)
			8  => 'image/tiff',                    // IMAGETYPE_TIFF_MM (motorola byte order)
			9  => 'application/octet-stream',      // IMAGETYPE_JPC
			10 => 'image/jp2',                     // IMAGETYPE_JP2
			11 => 'application/octet-stream',      // IMAGETYPE_JPX
			12 => 'application/octet-stream',      // IMAGETYPE_JB2
			13 => 'application/x-shockwave-flash', // IMAGETYPE_SWC
			14 => 'image/iff',                     // IMAGETYPE_IFF
			15 => 'image/vnd.wap.wbmp',            // IMAGETYPE_WBMP
			16 => 'image/xbm',                     // IMAGETYPE_XBM
			17 => 'image/x-icon',                  // IMAGETYPE_ICO
			18 => 'image/webp',                    // IMAGETYPE_WEBP
			19 => 'image/avif',                    // IMAGETYPE_AVIF

			'gif'  => 'image/gif',                 // IMAGETYPE_GIF
			'jpg'  => 'image/jpeg',                // IMAGETYPE_JPEG
			'jpeg' => 'image/jpeg',                // IMAGETYPE_JPEG
			'png'  => 'image/png',                 // IMAGETYPE_PNG
			'bmp'  => 'image/bmp',                 // IMAGETYPE_BMP
			'ico'  => 'image/x-icon',              // IMAGETYPE_ICO
			'webp' => 'image/webp',                // IMAGETYPE_WEBP
			'avif' => 'image/avif',                // IMAGETYPE_AVIF
		);

		return (isset($image_type_to_mime_type[$imagetype]) ? $image_type_to_mime_type[$imagetype] : false);
	}


	public static function TranslateWHbyAngle($width, $height, $angle) {
		if (($angle % 180) == 0) {
			return array($width, $height);
		}
		$newwidth  = (abs(sin(deg2rad($angle))) * $height) + (abs(cos(deg2rad($angle))) * $width);
		$newheight = (abs(sin(deg2rad($angle))) * $width)  + (abs(cos(deg2rad($angle))) * $height);
		return array($newwidth, $newheight);
	}

	public static function HexCharDisplay($string) {
		$len = strlen($string);
		$output = '';
		for ($i = 0; $i < $len; $i++) {
			$output .= ' 0x'.str_pad(dechex(ord($string[$i])), 2, '0', STR_PAD_LEFT);
		}
		return $output;
	}


	public static function IsHexColor($HexColorString) {
		return preg_match('#^[0-9A-F]{6}$#i', (string)$HexColorString);
	}


	public static function ImageColorAllocateAlphaSafe(&$gdimg_hexcolorallocate, $R, $G, $B, $alpha=false) {
		if (self::version_compare_replacement(PHP_VERSION, '4.3.2', '>=') && ($alpha !== false)) {
			return imagecolorallocatealpha($gdimg_hexcolorallocate, round($R), round($G), round($B), (int) $alpha);
		} else {
			return imagecolorallocate($gdimg_hexcolorallocate, $R, $G, $B);
		}
	}

	public static function ImageHexColorAllocate(&$gdimg_hexcolorallocate, $HexColorString, $dieOnInvalid=false, $alpha=false) {
		if (!is_resource($gdimg_hexcolorallocate) && !(is_object($gdimg_hexcolorallocate) && $gdimg_hexcolorallocate instanceOf \GdImage)) {
			die('$gdimg_hexcolorallocate is not a GD resource in ImageHexColorAllocate()');
		}
		if (self::IsHexColor($HexColorString)) {
			$R = hexdec(substr($HexColorString, 0, 2));
			$G = hexdec(substr($HexColorString, 2, 2));
			$B = hexdec(substr($HexColorString, 4, 2));
			return self::ImageColorAllocateAlphaSafe($gdimg_hexcolorallocate, $R, $G, $B, $alpha);
		}
		if ($dieOnInvalid) {
			die('Invalid hex color string: "'.$HexColorString.'"');
		}
		return imagecolorallocate($gdimg_hexcolorallocate, 0x00, 0x00, 0x00);
	}


	public static function HexColorXOR($hexcolor) {
		return strtoupper(str_pad(dechex(~hexdec($hexcolor) & 0xFFFFFF), 6, '0', STR_PAD_LEFT));
	}


	public static function GetPixelColor(&$img, $x, $y) {
		if (!is_resource($img) && !(is_object($img) && $img instanceOf \GdImage)) {
			return false;
		}
		return @imagecolorsforindex($img, @imagecolorat($img, $x, $y));
	}


	public static function PixelColorDifferencePercent($currentPixel, $targetPixel) {
		$diff = 0;
		foreach ($targetPixel as $channel => $currentvalue) {
			$diff = max($diff, (max($currentPixel[$channel], $targetPixel[$channel]) - min($currentPixel[$channel], $targetPixel[$channel])) / 255);
		}
		return $diff * 100;
	}

	public static function GrayscaleValue($r, $g, $b) {
		return round(($r * 0.30) + ($g * 0.59) + ($b * 0.11));
	}


	public static function GrayscalePixel($OriginalPixel) {
		$gray = self::GrayscaleValue($OriginalPixel[ 'red'], $OriginalPixel[ 'green'], $OriginalPixel[ 'blue']);
		return array('red'=>$gray, 'green'=>$gray, 'blue'=>$gray);
	}


	public static function GrayscalePixelRGB($rgb) {
		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >>  8) & 0xFF;
		$b =  $rgb        & 0xFF;
		return ($r * 0.299) + ($g * 0.587) + ($b * 0.114);
	}


	public static function ScaleToFitInBox($width, $height, $maxwidth=null, $maxheight=null, $allow_enlarge=true, $allow_reduce=true) {
		$maxwidth  = (null === $maxwidth  ? $width  : $maxwidth);
		$maxheight = (null === $maxheight ? $height : $maxheight);
		$scale_x = 1;
		$scale_y = 1;
		if (($width > $maxwidth) || ($width < $maxwidth)) {
			$scale_x = ($maxwidth / $width);
		}
		if (($height > $maxheight) || ($height < $maxheight)) {
			$scale_y = ($maxheight / $height);
		}
		$scale = min($scale_x, $scale_y);
		if (!$allow_enlarge) {
			$scale = min($scale, 1);
		}
		if (!$allow_reduce) {
			$scale = max($scale, 1);
		}
		return $scale;
	}

	public static function ImageCopyResampleBicubic($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {
		// ron at korving dot demon dot nl
		// http://www.php.net/imagecopyresampled

		$scaleX = ($src_w - 1) / $dst_w;
		$scaleY = ($src_h - 1) / $dst_h;

		$scaleX2 = $scaleX / 2.0;
		$scaleY2 = $scaleY / 2.0;

		$isTrueColor = imageistruecolor($src_img);

		for ($y = $src_y; $y < $src_y + $dst_h; $y++) {
			$sY   = $y * $scaleY;
			$siY  = (int) $sY;
			$siY2 = (int) $sY + $scaleY2;

			for ($x = $src_x; $x < $src_x + $dst_w; $x++) {
				$sX   = $x * $scaleX;
				$siX  = (int) $sX;
				$siX2 = (int) $sX + $scaleX2;

				if ($isTrueColor) {

					$c1 = imagecolorat($src_img, $siX, $siY2);
					$c2 = imagecolorat($src_img, $siX, $siY);
					$c3 = imagecolorat($src_img, $siX2, $siY2);
					$c4 = imagecolorat($src_img, $siX2, $siY);

					$r = (( $c1             +  $c2             +  $c3             +  $c4            ) >> 2) & 0xFF0000;
					$g = ((($c1 & 0x00FF00) + ($c2 & 0x00FF00) + ($c3 & 0x00FF00) + ($c4 & 0x00FF00)) >> 2) & 0x00FF00;
					$b = ((($c1 & 0x0000FF) + ($c2 & 0x0000FF) + ($c3 & 0x0000FF) + ($c4 & 0x0000FF)) >> 2);

				} else {

					$c1 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY2));
					$c2 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX, $siY));
					$c3 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY2));
					$c4 = imagecolorsforindex($src_img, imagecolorat($src_img, $siX2, $siY));

					$r = ($c1['red']   + $c2['red']   + $c3['red']   + $c4['red'] )  << 14;
					$g = ($c1['green'] + $c2['green'] + $c3['green'] + $c4['green']) <<  6;
					$b = ($c1['blue']  + $c2['blue']  + $c3['blue']  + $c4['blue'] ) >>  2;

				}
				imagesetpixel($dst_img, $dst_x + $x - $src_x, $dst_y + $y - $src_y, $r+$g+$b);
			}
		}
		return true;
	}


	public static function ImageCreateFunction($x_size, $y_size) {
		$ImageCreateFunction = 'imagecreate';
		if (self::gd_version() >= 2.0) {
			$ImageCreateFunction = 'imagecreatetruecolor';
		}
		if (!function_exists($ImageCreateFunction)) {
			return phpthumb::ErrorImage($ImageCreateFunction.'() does not exist - no GD support?');
		}
		if (($x_size <= 0) || ($y_size <= 0)) {
			return phpthumb::ErrorImage('Invalid image dimensions: '.$ImageCreateFunction.'('.$x_size.', '.$y_size.')');
		}
		return $ImageCreateFunction(round($x_size), round($y_size));
	}


	public static function ImageCopyRespectAlpha(&$dst_im, &$src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity_pct=100) {
		$opacipct = $opacity_pct / 100;
		for ($x = $src_x; $x < $src_w; $x++) {
			for ($y = $src_y; $y < $src_h; $y++) {
				$RealPixel    = self::GetPixelColor($dst_im, $dst_x + $x, $dst_y + $y);
				$OverlayPixel = self::GetPixelColor($src_im, $x, $y);
				$alphapct = $OverlayPixel['alpha'] / 127;
				$overlaypct = (1 - $alphapct) * $opacipct;

				$newcolor = self::ImageColorAllocateAlphaSafe(
					$dst_im,
					$RealPixel['alpha'] == 127 ? $OverlayPixel['red'] : ($OverlayPixel['alpha'] == 127 ? $RealPixel['red'] : (round($RealPixel['red'] * (1 - $overlaypct)) + ($OverlayPixel['red'] * $overlaypct))),
					$RealPixel['alpha'] == 127 ? $OverlayPixel['green'] : ($OverlayPixel['alpha'] == 127 ? $RealPixel['green'] : (round($RealPixel['green'] * (1 - $overlaypct)) + ($OverlayPixel['green'] * $overlaypct))),
					$RealPixel['alpha'] == 127 ? $OverlayPixel['blue'] : ($OverlayPixel['alpha'] == 127 ? $RealPixel['blue'] : (round($RealPixel['blue'] * (1 - $overlaypct)) + ($OverlayPixel['blue'] * $overlaypct))),
//					0);
					min([$RealPixel['alpha'], floor($OverlayPixel['alpha'] * $opacipct)])
				);

				imagesetpixel($dst_im, $dst_x + $x, $dst_y + $y, $newcolor);
			}
		}
		return true;
	}


	public static function ProportionalResize($old_width, $old_height, $new_width=false, $new_height=false) {
		$old_aspect_ratio = $old_width / $old_height;
		if (($new_width === false) && ($new_height === false)) {
			return false;
		} elseif ($new_width === false) {
			$new_width = $new_height * $old_aspect_ratio;
		} elseif ($new_height === false) {
			$new_height = $new_width / $old_aspect_ratio;
		}
		$new_aspect_ratio = $new_width / $new_height;
		if ($new_aspect_ratio == $old_aspect_ratio) {
			// great, done
		} elseif ($new_aspect_ratio < $old_aspect_ratio) {
			// limited by width
			$new_height = $new_width / $old_aspect_ratio;
		} elseif ($new_aspect_ratio > $old_aspect_ratio) {
			// limited by height
			$new_width = $new_height * $old_aspect_ratio;
		}
		return array(
			(int) round($new_width),
			(int) round($new_height)
		);
	}


	public static function FunctionIsDisabled($function) {
		static $DisabledFunctions = null;
		if (null === $DisabledFunctions) {
			$disable_functions_local  = explode(',',     strtolower(@ini_get('disable_functions')));
			$disable_functions_global = explode(',', strtolower(@get_cfg_var('disable_functions')));
			foreach ($disable_functions_local as $key => $value) {
				$DisabledFunctions[trim($value)] = 'local';
			}
			foreach ($disable_functions_global as $key => $value) {
				$DisabledFunctions[trim($value)] = 'global';
			}
			if (@ini_get('safe_mode')) {
				$DisabledFunctions['shell_exec']     = 'local';
				$DisabledFunctions['set_time_limit'] = 'local';
			}
		}
		return isset($DisabledFunctions[strtolower($function)]);
	}


	public static function SafeExec($command) {
		static $AllowedExecFunctions = array();
		if (empty($AllowedExecFunctions)) {
			$AllowedExecFunctions = array('shell_exec'=>true, 'passthru'=>true, 'system'=>true, 'exec'=>true);
			foreach ($AllowedExecFunctions as $key => $value) {
				$AllowedExecFunctions[$key] = !self::FunctionIsDisabled($key);
			}
		}
		$command .= ' 2>&1'; // force redirect stderr to stdout
		foreach ($AllowedExecFunctions as $execfunction => $is_allowed) {
			if (!$is_allowed) {
				continue;
			}
			$returnvalue = false;
			switch ($execfunction) {
				case 'passthru':
				case 'system':
					ob_start();
					$execfunction($command);
					$returnvalue = ob_get_contents();
					ob_end_clean();
					break;

				case 'exec':
					$output = array();
					$lastline = $execfunction($command, $output);
					$returnvalue = implode("\n", $output);
					break;

				case 'shell_exec':
					ob_start();
					$returnvalue = $execfunction($command);
					ob_end_clean();
					break;
			}
			return $returnvalue;
		}
		return false;
	}


	public static function ApacheLookupURIarray($filename) {
		// apache_lookup_uri() only works when PHP is installed as an Apache module.
		if (PHP_SAPI == 'apache') {
			//$property_exists_exists = function_exists('property_exists');
			$keys = array('status', 'the_request', 'status_line', 'method', 'content_type', 'handler', 'uri', 'filename', 'path_info', 'args', 'boundary', 'no_cache', 'no_local_copy', 'allowed', 'send_bodyct', 'bytes_sent', 'byterange', 'clength', 'unparsed_uri', 'mtime', 'request_time');
			if ($apacheLookupURIobject = @apache_lookup_uri($filename)) {
				$apacheLookupURIarray = array();
				foreach ($keys as $key) {
					$apacheLookupURIarray[$key] = @$apacheLookupURIobject->$key;
				}
				return $apacheLookupURIarray;
			}
		}
		return false;
	}


	public static function gd_is_bundled() {
		static $isbundled = null;
		if (null === $isbundled) {
			$gd_info = gd_info();
			$isbundled = (strpos($gd_info['GD Version'], 'bundled') !== false);
		}
		return $isbundled;
	}


	public static function gd_version($fullstring=false) {
		static $cache_gd_version = array();
		if (empty($cache_gd_version)) {
			$gd_info = gd_info();
			if (preg_match('#bundled \((.+)\)$#i', $gd_info['GD Version'], $matches)) {
				$cache_gd_version[1] = $gd_info['GD Version'];  // e.g. "bundled (2.0.15 compatible)"
				$cache_gd_version[0] = (float) $matches[1];     // e.g. "2.0" (not "bundled (2.0.15 compatible)")
			} else {
				$cache_gd_version[1] = $gd_info['GD Version'];                       // e.g. "1.6.2 or higher"
				$cache_gd_version[0] = (float) substr($gd_info['GD Version'], 0, 3); // e.g. "1.6" (not "1.6.2 or higher")
			}
		}
		return $cache_gd_version[ (int) $fullstring ];
	}


	public static function filesize_remote($remotefile, $timeout=10) {
		$size = false;
		$parsed_url = self::ParseURLbetter($remotefile);
		if ($fp = @fsockopen($parsed_url['host'], $parsed_url['port'], $errno, $errstr, $timeout)) {
			fwrite($fp, 'HEAD '.$parsed_url['path'].$parsed_url['query'].' HTTP/1.0'."\r\n".'Host: '.$parsed_url['host']."\r\n\r\n");
			if (self::version_compare_replacement(PHP_VERSION, '4.3.0', '>=')) {
				stream_set_timeout($fp, $timeout);
			}
			while (!feof($fp)) {
				$headerline = fgets($fp, 4096);
				if (preg_match('#^Content-Length: (.*)#i', $headerline, $matches)) {
					$size = (int) $matches[ 1];
					break;
				}
			}
			fclose ($fp);
		}
		return $size;
	}


	public static function filedate_remote($remotefile, $timeout=10) {
		$date = false;
		$parsed_url = self::ParseURLbetter($remotefile);
		if ($fp = @fsockopen($parsed_url['host'], $parsed_url['port'], $errno, $errstr, $timeout)) {
			fwrite($fp, 'HEAD '.$parsed_url['path'].$parsed_url['query'].' HTTP/1.0'."\r\n".'Host: '.$parsed_url['host']."\r\n\r\n");
			if (self::version_compare_replacement(PHP_VERSION, '4.3.0', '>=')) {
				stream_set_timeout($fp, $timeout);
			}
			while (!feof($fp)) {
				$headerline = fgets($fp, 4096);
				if (preg_match('#^Last-Modified: (.*)#i', $headerline, $matches)) {
					$date = strtotime($matches[1]) - date('Z');
					break;
				}
			}
			fclose ($fp);
		}
		return $date;
	}


	public static function md5_file_safe($filename) {
		// md5_file() doesn't exist in PHP < 4.2.0
		if (function_exists('md5_file')) {
			return md5_file($filename);
		}
		if ($fp = @fopen($filename, 'rb')) {
			$rawData = '';
			do {
				$buffer = fread($fp, 8192);
				$rawData .= $buffer;
			} while (strlen($buffer) > 0);
			fclose($fp);
			return md5($rawData);
		}
		return false;
	}


	public static function nonempty_min() {
		$arg_list = func_get_args();
		$acceptable = array();
		foreach ($arg_list as $arg) {
			if ($arg) {
				$acceptable[] = $arg;
			}
		}
		return min($acceptable);
	}


	public static function LittleEndian2String($number, $minbytes=1) {
		$intstring = '';
		while ($number > 0) {
			$intstring .= chr($number & 255);
			$number    >>= 8;
		}
		return str_pad($intstring, $minbytes, "\x00", STR_PAD_RIGHT);
	}

	public static function OneOfThese() {
		// return the first useful (non-empty/non-zero/non-false) value from those passed
		$arg_list = func_get_args();
		foreach ($arg_list as $key => $value) {
			if ($value) {
				return $value;
			}
		}
		return false;
	}

	public static function CaseInsensitiveInArray($needle, $haystack) {
		$needle = strtolower($needle);
		foreach ($haystack as $key => $value) {
			if (is_array($value)) {
				// skip?
			} elseif ($needle == strtolower($value)) {
				return true;
			}
		}
		return false;
	}

	public static function URLreadFsock($host, $file, &$errstr, $successonly=true, $port=-1, $timeout=10) {
		if (!function_exists('fsockopen') || self::FunctionIsDisabled('fsockopen')) {
			$errstr = 'URLreadFsock says: function fsockopen() unavailable';
			return false;
		}
		$port = (int) ($port ? $port : -1); // passing anything as the $port parameter (even empty values like null, false, 0, "") will override the default -1. fsockopen uses -1 as the default port value.
		//if ($fp = @fsockopen($host, $port, $errno, $errstr, $timeout)) {
		if ($fp = @fsockopen((($port == 443) ? 'ssl://' : '').$host, $port, $errno, $errstr, $timeout)) { // https://github.com/JamesHeinrich/phpThumb/issues/39
			$out  = 'GET '.$file.' HTTP/1.0'."\r\n";
			$out .= 'Host: '.$host."\r\n";
			$out .= 'Connection: Close'."\r\n\r\n";
			fwrite($fp, $out);

			$isHeader = true;
			$data_header = '';
			$data_body   = '';
			$header_newlocation = '';
			while (!feof($fp)) {
				$line = fgets($fp, 1024);
				if ($isHeader) {
					$data_header .= $line;
				} else {
					$data_body .= $line;
				}
				if (preg_match('#^HTTP/[\\.\d]+ ([\d]+)\s*(.+)?$#i', rtrim($line), $matches)) {
					list( , $errno, $errstr) = $matches;
					$errno = (int) $errno;
				} elseif (preg_match('#^Location: (.*)$#i', rtrim($line), $matches)) {
					$header_newlocation = $matches[1];
				}
				if ($isHeader && ($line == "\r\n")) {
					$isHeader = false;
					if ($successonly) {
						switch ($errno) {
							case 200:
								// great, continue
								break;

							default:
								$errstr = $errno.' '.$errstr.($header_newlocation ? '; Location: '.$header_newlocation : '');
								fclose($fp);
								return false;
								break;
						}
					}
				}
			}
			fclose($fp);
			return $data_body;
		}
		return null;
	}

	public static function CleanUpURLencoding($url, $queryseperator='&') {
		if (!0 === stripos($url, "http") ) {
			return $url;
		}
		$parsed_url = self::ParseURLbetter($url);
		$pathelements = explode('/', (string)$parsed_url['path']);
		$CleanPathElements = array();
		$TranslationMatrix = array(' '=>'%20');
		foreach ($pathelements as $key => $pathelement) {
			$CleanPathElements[] = strtr($pathelement, $TranslationMatrix);
		}
		foreach ($CleanPathElements as $key => $value) {
			if ($value === '') {
				unset($CleanPathElements[$key]);
			}
		}

		$queries = explode($queryseperator, (string)$parsed_url['query']);
		$CleanQueries = array();
		foreach ($queries as $key => $query) {
			@list($param, $value) = explode('=', $query);
			$CleanQueries[] = strtr($param, $TranslationMatrix).($value ? '='.strtr($value, $TranslationMatrix) : '');
		}
		foreach ($CleanQueries as $key => $value) {
			if ($value === '') {
				unset($CleanQueries[$key]);
			}
		}

		$cleaned_url  = $parsed_url['scheme'].'://';
		$cleaned_url .= ($parsed_url['user'] ? $parsed_url['user'].($parsed_url['pass'] ? ':'.$parsed_url['pass'] : '').'@' : '');
		$cleaned_url .= $parsed_url['host'];
		$cleaned_url .= (($parsed_url['port'] && ($parsed_url['port'] != self::URLschemeDefaultPort($parsed_url['scheme']))) ? ':'.$parsed_url['port'] : '');
		$cleaned_url .= '/'.implode('/', $CleanPathElements);
		$cleaned_url .= (!empty($CleanQueries) ? '?'.implode($queryseperator, $CleanQueries) : '');
		return $cleaned_url;
	}

	public static function URLschemeDefaultPort($scheme) {
		static $schemePort = array(
			'ftp'   => 21,
			'http'  => 80,
			'https' => 443,
		);
		return ((!empty($scheme) && isset($schemePort[strtolower($scheme)])) ? $schemePort[strtolower($scheme)] : null);
	}

	public static function ParseURLbetter($url) {
		$parsedURL = @parse_url($url);
		foreach (array('scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment') as $key) { // ensure all possible array keys are always returned
			if (!array_key_exists($key, $parsedURL)) {
				$parsedURL[$key] = null;
			}
		}
		$parsedURL['port'] = ($parsedURL['port'] ? $parsedURL['port'] : self::URLschemeDefaultPort($parsedURL['scheme']));
		return $parsedURL;
	}

	public static function SafeURLread($url, &$error, $timeout=10, $followredirects=true) {
		$error   = '';
		$errstr  = '';
		$rawData = '';

		$parsed_url = self::ParseURLbetter($url);
		$alreadyLookedAtURLs[trim($url)] = true;

		while (true) {
			$tryagain = false;
			$rawData = self::URLreadFsock($parsed_url['host'], $parsed_url['path'].'?'.$parsed_url['query'], $errstr, true, $parsed_url['port'], $timeout);
			if ($followredirects && preg_match('#302 [a-z ]+; Location\\: (http.*)#i', $errstr, $matches)) {
				$matches[1] = trim(@$matches[1]);
				if (!@$alreadyLookedAtURLs[$matches[1]]) {
					// loop through and examine new URL
					$error .= 'URL "'.$url.'" redirected to "'.$matches[1].'"';

					$tryagain = true;
					$alreadyLookedAtURLs[$matches[1]] = true;
					$parsed_url = self::ParseURLbetter($matches[ 1]);
				}
			}
			if (!$tryagain) {
				break;
			}
		}

		if ($rawData === false) {
			$error .= 'Error opening "'.$url.'":'."\n\n".$errstr;
			return false;
		} elseif ($rawData === null) {
			// fall through
			$error .= 'Error opening "'.$url.'":'."\n\n".$errstr;
		} else {
			return $rawData;
		}

		if (function_exists('curl_version') && !self::FunctionIsDisabled('curl_exec')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, (bool) $followredirects);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			$rawData = curl_exec($ch);
			curl_close($ch);
			if (strlen($rawData) > 0) {
				$error .= 'CURL succeeded ('.strlen($rawData).' bytes); ';
				return $rawData;
			}
			$error .= 'CURL available but returned no data; ';
		} else {
			$error .= 'CURL unavailable; ';
		}

		$BrokenURLfopenPHPversions = array('4.4.2');
		if (in_array(PHP_VERSION, $BrokenURLfopenPHPversions)) {
			$error .= 'fopen(URL) broken in PHP v'. PHP_VERSION .'; ';
		} elseif (@ini_get('allow_url_fopen')) {
			$rawData = '';
			$error_fopen = '';
			ob_start();
			if ($fp = fopen($url, 'rb')) {
				do {
					$buffer = fread($fp, 8192);
					$rawData .= $buffer;
				} while (strlen($buffer) > 0);
				fclose($fp);
			} else {
				$error_fopen .= trim(strip_tags(ob_get_contents()));
			}
			ob_end_clean();
			$error .= $error_fopen;
			if (!$error_fopen) {
				$error .= '; "allow_url_fopen" succeeded ('.strlen($rawData).' bytes); ';
				return $rawData;
			}
			$error .= '; "allow_url_fopen" enabled but returned no data ('.$error_fopen.'); ';
		} else {
			$error .= '"allow_url_fopen" disabled; ';
		}

		return false;
	}

	public static function EnsureDirectoryExists($dirname, $mask=0755) {
		// https://www.php.net/manual/en/ini.core.php#ini.open-basedir says:
		// "Under Windows, separate the directories with a semicolon. On all other systems, separate the directories with a colon."
		$config_open_basedir = ini_get('open_basedir');
		$startoffset = 2; // 1-based counting, first element to left of first directory separator will either be drive letter (Windows) or blank (unix). May be overridden below.
		if (self::is_windows()) {
			$delimiter = ';';
			$case_insensitive_pathname = true;
			// unix OSs will always use "/", some Windows configurations you may find "/" used interchangeably with the OS-correct "\", so standardize for ease of comparison
			$dirname             = str_replace('/', DIRECTORY_SEPARATOR, $dirname);
			$config_open_basedir = str_replace('/', DIRECTORY_SEPARATOR, $config_open_basedir);
		} else {
			$delimiter = ':';
			$case_insensitive_pathname = false;
		}
		do {
			/*
			\\3930K\WEBROOT\trainspotted.com\phpThumb/_cache/\6\6f    // starts off with mismatched directory separators
			\\3930K\WEBROOT\trainspotted.com\phpThumb\_cache\\6\6f    // gets multiple directory separators in a row that we want to strip out (being sure not to replace the UNC double-slash at the beginning)
			*/
			if ($doubleslash_offset = strpos($dirname, DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, 1)) {
				$dirname = substr($dirname, 0, $doubleslash_offset).substr($dirname, $doubleslash_offset + 1);
			}
		} while ($doubleslash_offset !== false);

		$open_basedirs = explode($delimiter, $config_open_basedir);
		foreach ($open_basedirs as $key => $open_basedir) {
			if (preg_match('#^'.preg_quote($open_basedir).'#'.($case_insensitive_pathname ? 'i' : ''), $dirname) && (strlen($dirname) > strlen($open_basedir))) {
				$startoffset = substr_count($open_basedir, DIRECTORY_SEPARATOR) + 1;
				break;
			}
		}

		$directory_elements = explode(DIRECTORY_SEPARATOR, $dirname);
		$endoffset = count($directory_elements);
		for ($i = $startoffset; $i <= $endoffset; $i++) {
			$test_directory = implode(DIRECTORY_SEPARATOR, array_slice($directory_elements, 0, $i));
			if (!$test_directory) {
				continue;
			}
			if (!@is_dir($test_directory)) {
				if (substr($test_directory, 0, 2) == '\\\\') {
					// UNC path
					if (count(explode('\\', $test_directory)) <= 4) {
						// 1,2 = UNC starting slashes
						// 3 = hostname; skip further checks
						// 4 = sharename; skip further checks
						// 5+ = real subdiretories
						continue;
					}
				}
				if (@file_exists($test_directory)) {
					// directory name already exists as a file
					return false;
				}
				@mkdir($test_directory, $mask);
				@chmod($test_directory, $mask);
				clearstatcache();
				if (!@is_dir($test_directory) || !@is_writable($test_directory)) {
					return false;
				}
			}
		}
		return true;
	}


	public static function GetAllFilesInSubfolders($dirname) {
		$AllFiles = array();
		$dirname = rtrim(realpath($dirname), '/\\');
		if ($dirhandle = @opendir($dirname)) {
			while (($file = readdir($dirhandle)) !== false) {
				$fullfilename = $dirname.DIRECTORY_SEPARATOR.$file;
				if (is_file($fullfilename)) {
					$AllFiles[] = $fullfilename;
				} elseif (is_dir($fullfilename)) {
					switch ($file) {
						case '.':
						case '..':
							break;

						default:
							$AllFiles[] = $fullfilename;
							$subfiles = self::GetAllFilesInSubfolders($fullfilename);
							foreach ($subfiles as $filename) {
								$AllFiles[] = $filename;
							}
							break;
					}
				} else {
					// ignore?
				}
			}
			closedir($dirhandle);
		}
		sort($AllFiles);
		return array_unique($AllFiles);
	}


	public static function SanitizeFilename($filename) {
		$filename = preg_replace('/[^'.preg_quote(' !#$%^()+,-.;<>=@[]_{}').'a-zA-Z0-9]/', '_', $filename);
		if (self::version_compare_replacement(PHP_VERSION, '4.1.0', '>=')) {
			$filename = trim($filename, '.');
		}
		return $filename;
	}

	public static function PasswordStrength($password) {
		$strength = 0;
		$strength += strlen(preg_replace('#[^a-z]#',       '', $password)) * 0.5; // lowercase characters are weak
		$strength += strlen(preg_replace('#[^A-Z]#',       '', $password)) * 0.8; // uppercase characters are somewhat better
		$strength += strlen(preg_replace('#[^0-9]#',       '', $password)) * 1.0; // numbers are somewhat better
		$strength += strlen(preg_replace('#[a-zA-Z0-9]#',  '', $password)) * 2.0; // other non-alphanumeric characters are best
		return $strength;
	}

}


////////////// END: class phpthumb_functions //////////////


if (!function_exists('gd_info')) {
	// built into PHP v4.3.0+ (with bundled GD2 library)
	function gd_info() {
		static $gd_info = array();
		if (empty($gd_info)) {
			// based on code by johnschaefer at gmx dot de
			// from PHP help on gd_info()
			$gd_info = array(
				'GD Version'         => '',
				'FreeType Support'   => false,
				'FreeType Linkage'   => '',
				'T1Lib Support'      => false,
				'GIF Read Support'   => false,
				'GIF Create Support' => false,
				'JPG Support'        => false,
				'PNG Support'        => false,
				'WBMP Support'       => false,
				'XBM Support'        => false
			);
			$phpinfo_array = phpthumb_functions::phpinfo_array();
			foreach ($phpinfo_array as $line) {
				$line = trim(strip_tags($line));
				foreach ($gd_info as $key => $value) {
					//if (strpos($line, $key) !== false) {
					if (strpos($line, $key) === 0) {
						$newvalue = trim(str_replace($key, '', $line));
						$gd_info[$key] = $newvalue;
					}
				}
			}
			if (empty($gd_info['GD Version'])) {
				// probable cause: "phpinfo() disabled for security reasons"
				if (function_exists('imagetypes')) {
					$imagetypes = imagetypes();
					if ($imagetypes & IMG_PNG) {
						$gd_info['PNG Support'] = true;
					}
					if ($imagetypes & IMG_GIF) {
						$gd_info['GIF Create Support'] = true;
					}
					if ($imagetypes & IMG_JPG) {
						$gd_info['JPG Support'] = true;
					}
					if ($imagetypes & IMG_WBMP) {
						$gd_info['WBMP Support'] = true;
					}
				}
				// to determine capability of GIF creation, try to use imagecreatefromgif on a 1px GIF
				if (function_exists('imagecreatefromgif')) {
					if ($tempfilename = phpthumb::phpThumb_tempnam()) {
						if ($fp_tempfile = @fopen($tempfilename, 'wb')) {
							fwrite($fp_tempfile, base64_decode('R0lGODlhAQABAIAAAH//AP///ywAAAAAAQABAAACAUQAOw==')); // very simple 1px GIF file base64-encoded as string
							fclose($fp_tempfile);
							$phpthumb_temp = new phpthumb();
							@chmod($tempfilename, $phpthumb_temp->getParameter('config_file_create_mask'));

							// if we can convert the GIF file to a GD image then GIF create support must be enabled, otherwise it's not
							$gd_info['GIF Read Support'] = (bool) @imagecreatefromgif($tempfilename);
						}
						unlink($tempfilename);
					}
				}
				if (function_exists('imagecreatetruecolor') && @imagecreatetruecolor(1, 1)) {
					$gd_info['GD Version'] = '2.0.1 or higher (assumed)';
				} elseif (function_exists('imagecreate') && @imagecreate(1, 1)) {
					$gd_info['GD Version'] = '1.6.0 or higher (assumed)';
				}
			}
		}
		return $gd_info;
	}
}


if (!function_exists('is_executable')) {
	// in PHP v3+, but v5.0+ for Windows
	function is_executable($filename) {
		// poor substitute, but better than nothing
		return file_exists($filename);
	}
}


if (!function_exists('preg_quote')) {
	// included in PHP v3.0.9+, but may be unavailable if not compiled in
	function preg_quote($string, $delimiter='\\') {
		static $preg_quote_array = array();
		if (empty($preg_quote_array)) {
			$escapeables = '.\\+*?[^]$(){}=!<>|:';
			for ($i = 0, $iMax = strlen($escapeables); $i < $iMax; $i++) {
				$strtr_preg_quote[$escapeables[$i]] = $delimiter.$escapeables[$i];
			}
		}
		return strtr($string, $strtr_preg_quote);
	}
}

if (!function_exists('file_get_contents')) {
	// included in PHP v4.3.0+
	function file_get_contents($filename) {
		if (preg_match('#^(ftp|https?)\://#i', $filename)) {
			return phpthumb_functions::SafeURLread($filename, $error);
		}
		if ($fp = @fopen($filename, 'rb')) {
			$rawData = '';
			do {
				$buffer = fread($fp, 8192);
				$rawData .= $buffer;
			} while (strlen($buffer) > 0);
			fclose($fp);
			return $rawData;
		}
		return false;
	}
}


if (!function_exists('file_put_contents')) {
	// included in PHP v5.0.0+
	function file_put_contents($filename, $filedata) {
		if ($fp = @fopen($filename, 'wb')) {
			fwrite($fp, $filedata);
			fclose($fp);
			return true;
		}
		return false;
	}
}

if (!function_exists('imagealphablending')) {
	// built-in function requires PHP v4.0.6+ *and* GD v2.0.1+
	function imagealphablending(&$img, $blendmode=true) {
		// do nothing, this function is declared here just to
		// prevent runtime errors if GD2 is not available
		return true;
	}
}

if (!function_exists('imagesavealpha')) {
	// built-in function requires PHP v4.3.2+ *and* GD v2.0.1+
	function imagesavealpha(&$img, $blendmode=true) {
		// do nothing, this function is declared here just to
		// prevent runtime errors if GD2 is not available
		return true;
	}
}
