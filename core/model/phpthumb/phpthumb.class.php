<?php
//////////////////////////////////////////////////////////////
//   phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net      //
//         and/or https://github.com/JamesHeinrich/phpThumb //
//////////////////////////////////////////////////////////////
///                                                         //
// See: phpthumb.readme.txt for usage instructions          //
//                                                         ///
//////////////////////////////////////////////////////////////

ob_start();
if (!include_once(dirname(__FILE__).'/phpthumb.functions.php')) {
	ob_end_flush();
	die('failed to include_once("'.realpath(dirname(__FILE__).'/phpthumb.functions.php').'")');
}
ob_end_clean();

class phpthumb {

	// public:
	// START PARAMETERS (for object mode and phpThumb.php)
	// See phpthumb.readme.txt for descriptions of what each of these values are
	var $src  = null;     // SouRCe filename
	var $new  = null;     // NEW image (phpThumb.php only)
	var $w    = null;     // Width
	var $h    = null;     // Height
	var $wp   = null;     // Width  (Portrait Images Only)
	var $hp   = null;     // Height (Portrait Images Only)
	var $wl   = null;     // Width  (Landscape Images Only)
	var $hl   = null;     // Height (Landscape Images Only)
	var $ws   = null;     // Width  (Square Images Only)
	var $hs   = null;     // Height (Square Images Only)
	var $f    = null;     // output image Format
	var $q    = 75;       // jpeg output Quality
	var $sx   = null;     // Source crop top-left X position
	var $sy   = null;     // Source crop top-left Y position
	var $sw   = null;     // Source crop Width
	var $sh   = null;     // Source crop Height
	var $zc   = null;     // Zoom Crop
	var $bc   = null;     // Border Color
	var $bg   = null;     // BackGround color
	var $fltr = array();  // FiLTeRs
	var $goto = null;     // GO TO url after processing
	var $err  = null;     // default ERRor image filename
	var $xto  = null;     // extract eXif Thumbnail Only
	var $ra   = null;     // Rotate by Angle
	var $ar   = null;     // Auto Rotate
	var $aoe  = null;     // Allow Output Enlargement
	var $far  = null;     // Fixed Aspect Ratio
	var $iar  = null;     // Ignore Aspect Ratio
	var $maxb = null;     // MAXimum Bytes
	var $down = null;     // DOWNload thumbnail filename
	var $md5s = null;     // MD5 hash of Source image
	var $sfn  = 0;        // Source Frame Number
	var $dpi  = 150;      // Dots Per Inch for vector source formats
	var $sia  = null;     // Save Image As filename

	var $file = null;     // >>>deprecated, DO NOT USE, will be removed in future versions<<<

	var $phpThumbDebug = null;
	// END PARAMETERS


	// public:
	// START CONFIGURATION OPTIONS (for object mode only)
	// See phpThumb.config.php for descriptions of what each of these settings do

	// * Directory Configuration
	var $config_cache_directory                      = null;
	var $config_cache_directory_depth                = 0;
	var $config_cache_disable_warning                = true;
	var $config_cache_source_enabled                 = false;
	var $config_cache_source_directory               = null;
	var $config_temp_directory                       = null;
	var $config_document_root                        = null;

	// * Default output configuration:
	var $config_output_format                        = 'jpeg';
	var $config_output_maxwidth                      = 0;
	var $config_output_maxheight                     = 0;
	var $config_output_interlace                     = true;

	// * Error message configuration
	var $config_error_image_width                    = 400;
	var $config_error_image_height                   = 100;
	var $config_error_message_image_default          = '';
	var $config_error_bgcolor                        = 'CCCCFF';
	var $config_error_textcolor                      = 'FF0000';
	var $config_error_fontsize                       = 1;
	var $config_error_die_on_error                   = false;
	var $config_error_silent_die_on_error            = false;
	var $config_error_die_on_source_failure          = true;

	// * Anti-Hotlink Configuration:
	var $config_nohotlink_enabled                    = true;
	var $config_nohotlink_valid_domains              = array();
	var $config_nohotlink_erase_image                = true;
	var $config_nohotlink_text_message               = 'Off-server thumbnailing is not allowed';
	// * Off-server Linking Configuration:
	var $config_nooffsitelink_enabled                = false;
	var $config_nooffsitelink_valid_domains          = array();
	var $config_nooffsitelink_require_refer          = false;
	var $config_nooffsitelink_erase_image            = true;
	var $config_nooffsitelink_watermark_src          = '';
	var $config_nooffsitelink_text_message           = 'Off-server linking is not allowed';

	// * Border & Background default colors
	var $config_border_hexcolor                      = '000000';
	var $config_background_hexcolor                  = 'FFFFFF';

	// * TrueType Fonts
	var $config_ttf_directory                        = './fonts';

	var $config_max_source_pixels                    = null;
	var $config_use_exif_thumbnail_for_speed         = false;
	var $allow_local_http_src                        = false;

	var $config_imagemagick_path                     = null;
	var $config_prefer_imagemagick                   = true;
	var $config_imagemagick_use_thumbnail            = true;

	var $config_cache_maxage                         = null;
	var $config_cache_maxsize                        = null;
	var $config_cache_maxfiles                       = null;
	var $config_cache_source_filemtime_ignore_local  = false;
	var $config_cache_source_filemtime_ignore_remote = true;
	var $config_cache_default_only_suffix            = false;
	var $config_cache_force_passthru                 = true;
	var $config_cache_prefix                         = '';    // default value set in the constructor below

	// * MySQL
	var $config_mysql_query                          = null;
	var $config_mysql_hostname                       = null;
	var $config_mysql_username                       = null;
	var $config_mysql_password                       = null;
	var $config_mysql_database                       = null;

	// * Security
	var $config_high_security_enabled                = true;
	var $config_high_security_password               = null;
	var $config_high_security_url_separator          = '&';
	var $config_disable_debug                        = true;
	var $config_allow_src_above_docroot              = false;
	var $config_allow_src_above_phpthumb             = true;
	var $config_auto_allow_symlinks                  = true;    // allow symlink target directories without explicitly whitelisting them
	var $config_additional_allowed_dirs              = array(); // additional directories to allow source images to be read from

	// * HTTP fopen
	var $config_http_fopen_timeout                   = 10;
	var $config_http_follow_redirect                 = true;

	// * Compatability
	var $config_disable_pathinfo_parsing             = false;
	var $config_disable_imagecopyresampled           = false;
	var $config_disable_onlycreateable_passthru      = false;

	var $config_http_user_agent                      = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7';

	// END CONFIGURATION OPTIONS


	// public: error messages (read-only; persistant)
	var $debugmessages = array();
	var $debugtiming   = array();
	var $fatalerror    = null;


	// private: (should not be modified directly)
	var $thumbnailQuality = 75;
	var $thumbnailFormat  = null;

	var $sourceFilename   = null;
	var $rawImageData     = null;
	var $IMresizedData    = null;
	var $outputImageData  = null;

	var $useRawIMoutput   = false;

	var $gdimg_output     = null;
	var $gdimg_source     = null;

	var $getimagesizeinfo = null;

	var $source_width  = null;
	var $source_height = null;

	var $thumbnailCropX = null;
	var $thumbnailCropY = null;
	var $thumbnailCropW = null;
	var $thumbnailCropH = null;

	var $exif_thumbnail_width  = null;
	var $exif_thumbnail_height = null;
	var $exif_thumbnail_type   = null;
	var $exif_thumbnail_data   = null;
	var $exif_raw_data         = null;

	var $thumbnail_width        = null;
	var $thumbnail_height       = null;
	var $thumbnail_image_width  = null;
	var $thumbnail_image_height = null;

	var $tempFilesToDelete = array();
	var $cache_filename    = null;

	var $AlphaCapableFormats = array('png', 'ico', 'gif');
	var $is_alpha = false;

	var $iswindows  = null;
	var $issafemode = null;

	var $phpthumb_version = '1.7.13-201406261000';

	//////////////////////////////////////////////////////////////////////

	// public: constructor
	function phpThumb() {
		$this->DebugTimingMessage('phpThumb() constructor', __FILE__, __LINE__);
		$this->DebugMessage('phpThumb() v'.$this->phpthumb_version, __FILE__, __LINE__);
		$this->config_max_source_pixels = round(max(intval(ini_get('memory_limit')), intval(get_cfg_var('memory_limit'))) * 1048576 * 0.20); // 20% of memory_limit
		$this->iswindows  = (bool) (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
		$this->issafemode = (bool) preg_match('#(1|ON)#i', ini_get('safe_mode'));
		$this->config_document_root = (!empty($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT']   : $this->config_document_root);
		$this->config_cache_prefix  = ( isset($_SERVER['SERVER_NAME'])   ? $_SERVER['SERVER_NAME'].'_' : '');

		$this->purgeTempFiles(); // purge existing temp files if re-initializing object

		$php_sapi_name = strtolower(function_exists('php_sapi_name') ? php_sapi_name() : '');
		if ($php_sapi_name == 'cli') {
			$this->config_allow_src_above_docroot = true;
		}

		if (!$this->config_disable_debug) {
			// if debug mode is enabled, force phpThumbDebug output, do not allow normal thumbnails to be generated
			$this->phpThumbDebug = (is_null($this->phpThumbDebug) ? 9 : max(1, intval($this->phpThumbDebug)));
		}
	}

	function __destruct() {
		$this->purgeTempFiles();
	}

	// public:
	function purgeTempFiles() {
		foreach ($this->tempFilesToDelete as $tempFileToDelete) {
			if (file_exists($tempFileToDelete)) {
				$this->DebugMessage('Deleting temp file "'.$tempFileToDelete.'"', __FILE__, __LINE__);
				@unlink($tempFileToDelete);
			}
		}
		$this->tempFilesToDelete = array();
		return true;
	}

	// public:
	function setSourceFilename($sourceFilename) {
		//$this->resetObject();
		//$this->rawImageData   = null;
		$this->sourceFilename = $sourceFilename;
		$this->src            = $sourceFilename;
		if (is_null($this->config_output_format)) {
			$sourceFileExtension = strtolower(substr(strrchr($sourceFilename, '.'), 1));
			if (preg_match('#^[a-z]{3,4}$#', $sourceFileExtension)) {
				$this->config_output_format = $sourceFileExtension;
				$this->DebugMessage('setSourceFilename('.$sourceFilename.') set $this->config_output_format to "'.$sourceFileExtension.'"', __FILE__, __LINE__);
			} else {
				$this->DebugMessage('setSourceFilename('.$sourceFilename.') did NOT set $this->config_output_format to "'.$sourceFileExtension.'" because it did not seem like an appropriate image format', __FILE__, __LINE__);
			}
		}
		$this->DebugMessage('setSourceFilename('.$sourceFilename.') set $this->sourceFilename to "'.$this->sourceFilename.'"', __FILE__, __LINE__);
		return true;
	}

	// public:
	function setSourceData($rawImageData, $sourceFilename='') {
		//$this->resetObject();
		//$this->sourceFilename = null;
		$this->rawImageData   = $rawImageData;
		$this->DebugMessage('setSourceData() setting $this->rawImageData ('.strlen($this->rawImageData).' bytes; magic="'.substr($this->rawImageData, 0, 4).'" ('.phpthumb_functions::HexCharDisplay(substr($this->rawImageData, 0, 4)).'))', __FILE__, __LINE__);
		if ($this->config_cache_source_enabled) {
			$sourceFilename = ($sourceFilename ? $sourceFilename : md5($rawImageData));
			if (!is_dir($this->config_cache_source_directory)) {
				$this->ErrorImage('$this->config_cache_source_directory ('.$this->config_cache_source_directory.') is not a directory');
			} elseif (!@is_writable($this->config_cache_source_directory)) {
				$this->ErrorImage('$this->config_cache_source_directory ('.$this->config_cache_source_directory.') is not writable');
			}
			$this->DebugMessage('setSourceData() attempting to save source image to "'.$this->config_cache_source_directory.DIRECTORY_SEPARATOR.urlencode($sourceFilename).'"', __FILE__, __LINE__);
			if ($fp = @fopen($this->config_cache_source_directory.DIRECTORY_SEPARATOR.urlencode($sourceFilename), 'wb')) {
				fwrite($fp, $rawImageData);
				fclose($fp);
			} elseif (!$this->phpThumbDebug) {
				$this->ErrorImage('setSourceData() failed to write to source cache ('.$this->config_cache_source_directory.DIRECTORY_SEPARATOR.urlencode($sourceFilename).')');
			}
		}
		return true;
	}

	// public:
	function setSourceImageResource($gdimg) {
		//$this->resetObject();
		$this->gdimg_source = $gdimg;
		return true;
	}

	// public:
	function setParameter($param, $value) {
		if ($param == 'src') {
			$this->setSourceFilename($this->ResolveFilenameToAbsolute($value));
		} elseif (@is_array($this->$param)) {
			if (is_array($value)) {
				foreach ($value as $arraykey => $arrayvalue) {
					array_push($this->$param, $arrayvalue);
				}
			} else {
				array_push($this->$param, $value);
			}
		} else {
			$this->$param = $value;
		}
		return true;
	}

	// public:
	function getParameter($param) {
		//if (property_exists('phpThumb', $param)) {
			return $this->$param;
		//}
		//$this->DebugMessage('setParameter() attempting to get non-existant parameter "'.$param.'"', __FILE__, __LINE__);
		//return false;
	}


	// public:
	function GenerateThumbnail() {

		$this->setOutputFormat();
			$this->phpThumbDebug('8a');
		$this->ResolveSource();
			$this->phpThumbDebug('8b');
		$this->SetCacheFilename();
			$this->phpThumbDebug('8c');
		$this->ExtractEXIFgetImageSize();
			$this->phpThumbDebug('8d');
		if ($this->useRawIMoutput) {
			$this->DebugMessage('Skipping rest of GenerateThumbnail() because ($this->useRawIMoutput == true)', __FILE__, __LINE__);
			return true;
		}
			$this->phpThumbDebug('8e');
		if (!$this->SourceImageToGD()) {
			$this->DebugMessage('SourceImageToGD() failed', __FILE__, __LINE__);
			return false;
		}
			$this->phpThumbDebug('8f');
		$this->Rotate();
			$this->phpThumbDebug('8g');
		$this->CreateGDoutput();
			$this->phpThumbDebug('8h');

		// default values, also applicable for far="C"
		$destination_offset_x = round(($this->thumbnail_width  - $this->thumbnail_image_width)  / 2);
		$destination_offset_y = round(($this->thumbnail_height - $this->thumbnail_image_height) / 2);
		if (($this->far == 'L') || ($this->far == 'TL') || ($this->far == 'BL')) {
			$destination_offset_x = 0;
		}
		if (($this->far == 'R') || ($this->far == 'TR') || ($this->far == 'BR')) {
			$destination_offset_x =  round($this->thumbnail_width  - $this->thumbnail_image_width);
		}
		if (($this->far == 'T') || ($this->far == 'TL') || ($this->far == 'TR')) {
			$destination_offset_y = 0;
		}
		if (($this->far == 'B') || ($this->far == 'BL') || ($this->far == 'BR')) {
			$destination_offset_y =  round($this->thumbnail_height - $this->thumbnail_image_height);
		}

//		// copy/resize image to appropriate dimensions
//		$borderThickness = 0;
//		if (!empty($this->fltr)) {
//			foreach ($this->fltr as $key => $value) {
//				if (preg_match('#^bord\|([0-9]+)#', $value, $matches)) {
//					$borderThickness = $matches[1];
//					break;
//				}
//			}
//		}
//		if ($borderThickness > 0) {
//			//$this->DebugMessage('Skipping ImageResizeFunction() because BorderThickness="'.$borderThickness.'"', __FILE__, __LINE__);
//			$this->thumbnail_image_height /= 2;
//		}
		$this->ImageResizeFunction(
			$this->gdimg_output,
			$this->gdimg_source,
			$destination_offset_x,
			$destination_offset_y,
			$this->thumbnailCropX,
			$this->thumbnailCropY,
			$this->thumbnail_image_width,
			$this->thumbnail_image_height,
			$this->thumbnailCropW,
			$this->thumbnailCropH
		);

		$this->DebugMessage('memory_get_usage() after copy-resize = '.(function_exists('memory_get_usage') ? @memory_get_usage() : 'n/a'), __FILE__, __LINE__);
		ImageDestroy($this->gdimg_source);
		$this->DebugMessage('memory_get_usage() after ImageDestroy = '.(function_exists('memory_get_usage') ? @memory_get_usage() : 'n/a'), __FILE__, __LINE__);

			$this->phpThumbDebug('8i');
		$this->AntiOffsiteLinking();
			$this->phpThumbDebug('8j');
		$this->ApplyFilters();
			$this->phpThumbDebug('8k');
		$this->AlphaChannelFlatten();
			$this->phpThumbDebug('8l');
		$this->MaxFileSize();
			$this->phpThumbDebug('8m');

		$this->DebugMessage('GenerateThumbnail() completed successfully', __FILE__, __LINE__);
		return true;
	}


	// public:
	function RenderOutput() {
		if (!$this->useRawIMoutput && !is_resource($this->gdimg_output)) {
			$this->DebugMessage('RenderOutput() failed because !is_resource($this->gdimg_output)', __FILE__, __LINE__);
			return false;
		}
		if (!$this->thumbnailFormat) {
			$this->DebugMessage('RenderOutput() failed because $this->thumbnailFormat is empty', __FILE__, __LINE__);
			return false;
		}
		if ($this->useRawIMoutput) {
			$this->DebugMessage('RenderOutput copying $this->IMresizedData ('.strlen($this->IMresizedData).' bytes) to $this->outputImage', __FILE__, __LINE__);
			$this->outputImageData = $this->IMresizedData;
			return true;
		}

		$builtin_formats = array();
		if (function_exists('ImageTypes')) {
			$imagetypes = ImageTypes();
			$builtin_formats['wbmp'] = (bool) ($imagetypes & IMG_WBMP);
			$builtin_formats['jpg']  = (bool) ($imagetypes & IMG_JPG);
			$builtin_formats['gif']  = (bool) ($imagetypes & IMG_GIF);
			$builtin_formats['png']  = (bool) ($imagetypes & IMG_PNG);
		}
		$this->DebugMessage('RenderOutput() attempting Image'.strtoupper(@$this->thumbnailFormat).'($this->gdimg_output)', __FILE__, __LINE__);
		ob_start();
		switch ($this->thumbnailFormat) {
			case 'wbmp':
				if (!@$builtin_formats['wbmp']) {
					$this->DebugMessage('GD does not have required built-in support for WBMP output', __FILE__, __LINE__);
					ob_end_clean();
					return false;
				}
				ImageJPEG($this->gdimg_output, null, $this->thumbnailQuality);
				$this->outputImageData = ob_get_contents();
				break;

			case 'jpeg':
			case 'jpg':  // should be "jpeg" not "jpg" but just in case...
				if (!@$builtin_formats['jpg']) {
					$this->DebugMessage('GD does not have required built-in support for JPEG output', __FILE__, __LINE__);
					ob_end_clean();
					return false;
				}
				ImageJPEG($this->gdimg_output, null, $this->thumbnailQuality);
				$this->outputImageData = ob_get_contents();
				break;

			case 'png':
				if (!@$builtin_formats['png']) {
					$this->DebugMessage('GD does not have required built-in support for PNG output', __FILE__, __LINE__);
					ob_end_clean();
					return false;
				}
				ImagePNG($this->gdimg_output);
				$this->outputImageData = ob_get_contents();
				break;

			case 'gif':
				if (!@$builtin_formats['gif']) {
					$this->DebugMessage('GD does not have required built-in support for GIF output', __FILE__, __LINE__);
					ob_end_clean();
					return false;
				}
				ImageGIF($this->gdimg_output);
				$this->outputImageData = ob_get_contents();
				break;

			case 'bmp':
				$ImageOutFunction = '"builtin BMP output"';
				if (!@include_once(dirname(__FILE__).'/phpthumb.bmp.php')) {
					$this->DebugMessage('Error including "'.dirname(__FILE__).'/phpthumb.bmp.php" which is required for BMP format output', __FILE__, __LINE__);
					ob_end_clean();
					return false;
				}
				$phpthumb_bmp = new phpthumb_bmp();
				$this->outputImageData = $phpthumb_bmp->GD2BMPstring($this->gdimg_output);
				unset($phpthumb_bmp);
				break;

			case 'ico':
				$ImageOutFunction = '"builtin ICO output"';
				if (!@include_once(dirname(__FILE__).'/phpthumb.ico.php')) {
					$this->DebugMessage('Error including "'.dirname(__FILE__).'/phpthumb.ico.php" which is required for ICO format output', __FILE__, __LINE__);
					ob_end_clean();
					return false;
				}
				$phpthumb_ico = new phpthumb_ico();
				$arrayOfOutputImages = array($this->gdimg_output);
				$this->outputImageData = $phpthumb_ico->GD2ICOstring($arrayOfOutputImages);
				unset($phpthumb_ico);
				break;

			default:
				$this->DebugMessage('RenderOutput failed because $this->thumbnailFormat "'.$this->thumbnailFormat.'" is not valid', __FILE__, __LINE__);
				ob_end_clean();
				return false;
		}
		ob_end_clean();
		if (!$this->outputImageData) {
			$this->DebugMessage('RenderOutput() for "'.$this->thumbnailFormat.'" failed', __FILE__, __LINE__);
			ob_end_clean();
			return false;
		}
		$this->DebugMessage('RenderOutput() completing with $this->outputImageData = '.strlen($this->outputImageData).' bytes', __FILE__, __LINE__);
		return true;
	}


	// public:
	function RenderToFile($filename) {
		if (preg_match('#^[a-z0-9]+://#i', $filename)) {
			$this->DebugMessage('RenderToFile() failed because $filename ('.$filename.') is a URL', __FILE__, __LINE__);
			return false;
		}
		// render thumbnail to this file only, do not cache, do not output to browser
		//$renderfilename = $this->ResolveFilenameToAbsolute(dirname($filename)).DIRECTORY_SEPARATOR.basename($filename);
		$renderfilename = $filename;
		if (($filename{0} != '/') && ($filename{0} != '\\') && ($filename{1} != ':')) {
			$renderfilename = $this->ResolveFilenameToAbsolute($renderfilename);
		}
		if (!@is_writable(dirname($renderfilename))) {
			$this->DebugMessage('RenderToFile() failed because "'.dirname($renderfilename).'/" is not writable', __FILE__, __LINE__);
			return false;
		}
		if (@is_file($renderfilename) && !@is_writable($renderfilename)) {
			$this->DebugMessage('RenderToFile() failed because "'.$renderfilename.'" is not writable', __FILE__, __LINE__);
			return false;
		}

		if ($this->RenderOutput()) {
			if (file_put_contents($renderfilename, $this->outputImageData)) {
				$this->DebugMessage('RenderToFile('.$renderfilename.') succeeded', __FILE__, __LINE__);
				return true;
			}
			if (!@file_exists($renderfilename)) {
				$this->DebugMessage('RenderOutput ['.$this->thumbnailFormat.'('.$renderfilename.')] did not appear to fail, but the output image does not exist either...', __FILE__, __LINE__);
			}
		} else {
			$this->DebugMessage('RenderOutput ['.$this->thumbnailFormat.'('.$renderfilename.')] failed', __FILE__, __LINE__);
		}
		return false;
	}


	// public:
	function OutputThumbnail() {
		$this->purgeTempFiles();

		if (!$this->useRawIMoutput && !is_resource($this->gdimg_output)) {
			$this->DebugMessage('OutputThumbnail() failed because !is_resource($this->gdimg_output)', __FILE__, __LINE__);
			return false;
		}
		if (headers_sent()) {
			return $this->ErrorImage('OutputThumbnail() failed - headers already sent');
			exit;
		}

		$downloadfilename = phpthumb_functions::SanitizeFilename(is_string($this->sia) ? $this->sia : ($this->down ? $this->down : 'phpThumb_generated_thumbnail'.'.'.$this->thumbnailFormat));
		$this->DebugMessage('Content-Disposition header filename set to "'.$downloadfilename.'"', __FILE__, __LINE__);
		if ($downloadfilename) {
			header('Content-Disposition: '.($this->down ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"');
		} else {
			$this->DebugMessage('failed to send Content-Disposition header because $downloadfilename is empty', __FILE__, __LINE__);
		}

		if ($this->useRawIMoutput) {

			header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($this->thumbnailFormat));
			echo $this->IMresizedData;

		} else {

			$this->DebugMessage('ImageInterlace($this->gdimg_output, '.intval($this->config_output_interlace).')', __FILE__, __LINE__);
			ImageInterlace($this->gdimg_output, intval($this->config_output_interlace));
			switch ($this->thumbnailFormat) {
				case 'jpeg':
					header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($this->thumbnailFormat));
					$ImageOutFunction = 'image'.$this->thumbnailFormat;
					@$ImageOutFunction($this->gdimg_output, null, $this->thumbnailQuality);
					break;

				case 'png':
				case 'gif':
					header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($this->thumbnailFormat));
					$ImageOutFunction = 'image'.$this->thumbnailFormat;
					@$ImageOutFunction($this->gdimg_output);
					break;

				case 'bmp':
					if (!@include_once(dirname(__FILE__).'/phpthumb.bmp.php')) {
						$this->DebugMessage('Error including "'.dirname(__FILE__).'/phpthumb.bmp.php" which is required for BMP format output', __FILE__, __LINE__);
						return false;
					}
					$phpthumb_bmp = new phpthumb_bmp();
					if (is_object($phpthumb_bmp)) {
						$bmp_data = $phpthumb_bmp->GD2BMPstring($this->gdimg_output);
						unset($phpthumb_bmp);
						if (!$bmp_data) {
							$this->DebugMessage('$phpthumb_bmp->GD2BMPstring() failed', __FILE__, __LINE__);
							return false;
						}
						header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($this->thumbnailFormat));
						echo $bmp_data;
					} else {
						$this->DebugMessage('new phpthumb_bmp() failed', __FILE__, __LINE__);
						return false;
					}
					break;

				case 'ico':
					if (!@include_once(dirname(__FILE__).'/phpthumb.ico.php')) {
						$this->DebugMessage('Error including "'.dirname(__FILE__).'/phpthumb.ico.php" which is required for ICO format output', __FILE__, __LINE__);
						return false;
					}
					$phpthumb_ico = new phpthumb_ico();
					if (is_object($phpthumb_ico)) {
						$arrayOfOutputImages = array($this->gdimg_output);
						$ico_data = $phpthumb_ico->GD2ICOstring($arrayOfOutputImages);
						unset($phpthumb_ico);
						if (!$ico_data) {
							$this->DebugMessage('$phpthumb_ico->GD2ICOstring() failed', __FILE__, __LINE__);
							return false;
						}
						header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($this->thumbnailFormat));
						echo $ico_data;
					} else {
						$this->DebugMessage('new phpthumb_ico() failed', __FILE__, __LINE__);
						return false;
					}
					break;

				default:
					$this->DebugMessage('OutputThumbnail failed because $this->thumbnailFormat "'.$this->thumbnailFormat.'" is not valid', __FILE__, __LINE__);
					return false;
					break;
			}

		}
		return true;
	}


	// public:
	function CleanUpCacheDirectory() {
		$this->DebugMessage('CleanUpCacheDirectory() set to purge ('.(is_null($this->config_cache_maxage) ? 'NULL' : number_format($this->config_cache_maxage / 86400, 1)).' days; '.(is_null($this->config_cache_maxsize) ? 'NULL' : number_format($this->config_cache_maxsize / 1048576, 2)).' MB; '.(is_null($this->config_cache_maxfiles) ? 'NULL' : number_format($this->config_cache_maxfiles)).' files)', __FILE__, __LINE__);

		if (!is_writable($this->config_cache_directory)) {
			$this->DebugMessage('CleanUpCacheDirectory() skipped because "'.$this->config_cache_directory.'" is not writable', __FILE__, __LINE__);
			return true;
		}

		// cache status of cache directory for 1 hour to avoid hammering the filesystem functions
		$phpThumbCacheStats_filename = $this->config_cache_directory.DIRECTORY_SEPARATOR.'phpThumbCacheStats.txt';
		if (file_exists($phpThumbCacheStats_filename) && is_readable($phpThumbCacheStats_filename) && (filemtime($phpThumbCacheStats_filename) >= (time() - 3600))) {
			$this->DebugMessage('CleanUpCacheDirectory() skipped because "'.$phpThumbCacheStats_filename.'" is recently modified', __FILE__, __LINE__);
			return true;
		}
		if (!@touch($phpThumbCacheStats_filename)) {
			$this->DebugMessage('touch('.$phpThumbCacheStats_filename.') failed', __FILE__, __LINE__);
		}

		$DeletedKeys = array();
		$AllFilesInCacheDirectory = array();
		if (($this->config_cache_maxage > 0) || ($this->config_cache_maxsize > 0) || ($this->config_cache_maxfiles > 0)) {
			$CacheDirOldFilesAge  = array();
			$CacheDirOldFilesSize = array();
			$AllFilesInCacheDirectory = phpthumb_functions::GetAllFilesInSubfolders($this->config_cache_directory);
			foreach ($AllFilesInCacheDirectory as $fullfilename) {
				if (preg_match('#'.preg_quote($this->config_cache_prefix).'#i', $fullfilename) && file_exists($fullfilename)) {
					$CacheDirOldFilesAge[$fullfilename] = @fileatime($fullfilename);
					if ($CacheDirOldFilesAge[$fullfilename] == 0) {
						$CacheDirOldFilesAge[$fullfilename] = @filemtime($fullfilename);
					}
					$CacheDirOldFilesSize[$fullfilename] = @filesize($fullfilename);
				}
			}
			if (empty($CacheDirOldFilesSize)) {
				$this->DebugMessage('CleanUpCacheDirectory() skipped because $CacheDirOldFilesSize is empty (phpthumb_functions::GetAllFilesInSubfolders('.$this->config_cache_directory.') found no files)', __FILE__, __LINE__);
				return true;
			}
			$DeletedKeys['zerobyte'] = array();
			foreach ($CacheDirOldFilesSize as $fullfilename => $filesize) {
				// purge all zero-size files more than an hour old (to prevent trying to delete just-created and/or in-use files)
				$cutofftime = time() - 3600;
				if (($filesize == 0) && ($CacheDirOldFilesAge[$fullfilename] < $cutofftime)) {
					$this->DebugMessage('deleting "'.$fullfilename.'"', __FILE__, __LINE__);
					if (@unlink($fullfilename)) {
						$DeletedKeys['zerobyte'][] = $fullfilename;
						unset($CacheDirOldFilesSize[$fullfilename]);
						unset($CacheDirOldFilesAge[$fullfilename]);
					}
				}
			}
			$this->DebugMessage('CleanUpCacheDirectory() purged '.count($DeletedKeys['zerobyte']).' zero-byte files', __FILE__, __LINE__);
			asort($CacheDirOldFilesAge);

			if ($this->config_cache_maxfiles > 0) {
				$TotalCachedFiles = count($CacheDirOldFilesAge);
				$DeletedKeys['maxfiles'] = array();
				foreach ($CacheDirOldFilesAge as $fullfilename => $filedate) {
					if ($TotalCachedFiles > $this->config_cache_maxfiles) {
						$this->DebugMessage('deleting "'.$fullfilename.'"', __FILE__, __LINE__);
						if (@unlink($fullfilename)) {
							$TotalCachedFiles--;
							$DeletedKeys['maxfiles'][] = $fullfilename;
						}
					} else {
						// there are few enough files to keep the rest
						break;
					}
				}
				$this->DebugMessage('CleanUpCacheDirectory() purged '.count($DeletedKeys['maxfiles']).' files based on (config_cache_maxfiles='.$this->config_cache_maxfiles.')', __FILE__, __LINE__);
				foreach ($DeletedKeys['maxfiles'] as $fullfilename) {
					unset($CacheDirOldFilesAge[$fullfilename]);
					unset($CacheDirOldFilesSize[$fullfilename]);
				}
			}

			if ($this->config_cache_maxage > 0) {
				$mindate = time() - $this->config_cache_maxage;
				$DeletedKeys['maxage'] = array();
				foreach ($CacheDirOldFilesAge as $fullfilename => $filedate) {
					if ($filedate > 0) {
						if ($filedate < $mindate) {
							$this->DebugMessage('deleting "'.$fullfilename.'"', __FILE__, __LINE__);
							if (@unlink($fullfilename)) {
								$DeletedKeys['maxage'][] = $fullfilename;
							}
						} else {
							// the rest of the files are new enough to keep
							break;
						}
					}
				}
				$this->DebugMessage('CleanUpCacheDirectory() purged '.count($DeletedKeys['maxage']).' files based on (config_cache_maxage='.$this->config_cache_maxage.')', __FILE__, __LINE__);
				foreach ($DeletedKeys['maxage'] as $fullfilename) {
					unset($CacheDirOldFilesAge[$fullfilename]);
					unset($CacheDirOldFilesSize[$fullfilename]);
				}
			}

			if ($this->config_cache_maxsize > 0) {
				$TotalCachedFileSize = array_sum($CacheDirOldFilesSize);
				$DeletedKeys['maxsize'] = array();
				foreach ($CacheDirOldFilesAge as $fullfilename => $filedate) {
					if ($TotalCachedFileSize > $this->config_cache_maxsize) {
						$this->DebugMessage('deleting "'.$fullfilename.'"', __FILE__, __LINE__);
						if (@unlink($fullfilename)) {
							$TotalCachedFileSize -= $CacheDirOldFilesSize[$fullfilename];
							$DeletedKeys['maxsize'][] = $fullfilename;
						}
					} else {
						// the total filesizes are small enough to keep the rest of the files
						break;
					}
				}
				$this->DebugMessage('CleanUpCacheDirectory() purged '.count($DeletedKeys['maxsize']).' files based on (config_cache_maxsize='.$this->config_cache_maxsize.')', __FILE__, __LINE__);
				foreach ($DeletedKeys['maxsize'] as $fullfilename) {
					unset($CacheDirOldFilesAge[$fullfilename]);
					unset($CacheDirOldFilesSize[$fullfilename]);
				}
			}

		} else {
			$this->DebugMessage('skipping CleanUpCacheDirectory() because config set to not use it', __FILE__, __LINE__);
		}
		$totalpurged = 0;
		foreach ($DeletedKeys as $key => $value) {
			$totalpurged += count($value);
		}
		$this->DebugMessage('CleanUpCacheDirectory() purged '.$totalpurged.' files (from '.count($AllFilesInCacheDirectory).') based on config settings', __FILE__, __LINE__);
		if ($totalpurged > 0) {
			$empty_dirs = array();
			foreach ($AllFilesInCacheDirectory as $fullfilename) {
				if (is_dir($fullfilename)) {
					$empty_dirs[realpath($fullfilename)] = 1;
				} else {
					unset($empty_dirs[realpath(dirname($fullfilename))]);
				}
			}
			krsort($empty_dirs);
			$totalpurgeddirs = 0;
			foreach ($empty_dirs as $empty_dir => $dummy) {
				if ($empty_dir == $this->config_cache_directory) {
					// shouldn't happen, but just in case, don't let it delete actual cache directory
					continue;
				} elseif (@rmdir($empty_dir)) {
					$totalpurgeddirs++;
				} else {
					$this->DebugMessage('failed to rmdir('.$empty_dir.')', __FILE__, __LINE__);
				}
			}
			$this->DebugMessage('purged '.$totalpurgeddirs.' empty directories', __FILE__, __LINE__);
		}
		return true;
	}

	//////////////////////////////////////////////////////////////////////

	// private: re-initializator (call between rendering multiple images with one object)
	function resetObject() {
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $key => $value) {
			// do not clobber debug or config info
			if (!preg_match('#^(config_|debug|fatalerror)#i', $key)) {
				$this->$key = $value;
			}
		}
		$this->phpThumb(); // re-initialize some class variables
		return true;
	}

	//////////////////////////////////////////////////////////////////////

	function ResolveSource() {
		if (is_resource($this->gdimg_source)) {
			$this->DebugMessage('ResolveSource() exiting because is_resource($this->gdimg_source)', __FILE__, __LINE__);
			return true;
		}
		if ($this->rawImageData) {
			$this->sourceFilename = null;
			$this->DebugMessage('ResolveSource() exiting because $this->rawImageData is set ('.number_format(strlen($this->rawImageData)).' bytes)', __FILE__, __LINE__);
			return true;
		}
		if ($this->sourceFilename) {
			$this->sourceFilename = $this->ResolveFilenameToAbsolute($this->sourceFilename);
			$this->DebugMessage('$this->sourceFilename set to "'.$this->sourceFilename.'"', __FILE__, __LINE__);
		} elseif ($this->src) {
			$this->sourceFilename = $this->ResolveFilenameToAbsolute($this->src);
			$this->DebugMessage('$this->sourceFilename set to "'.$this->sourceFilename.'" from $this->src ('.$this->src.')', __FILE__, __LINE__);
		} else {
			return $this->ErrorImage('$this->sourceFilename and $this->src are both empty');
		}
		if ($this->iswindows && ((substr($this->sourceFilename, 0, 2) == '//') || (substr($this->sourceFilename, 0, 2) == '\\\\'))) {
			// Windows \\share\filename.ext
		} elseif (preg_match('#^[a-z0-9]+://#i', $this->sourceFilename, $protocol_matches)) {
			if (preg_match('#^(f|ht)tps?\://#i', $this->sourceFilename)) {
				// URL
				if ($this->config_http_user_agent) {
					ini_set('user_agent', $this->config_http_user_agent);
				}
			} else {
				return $this->ErrorImage('only FTP and HTTP/HTTPS protocols are allowed, "'.$protocol_matches[1].'" is not');
		}
		} elseif (!@file_exists($this->sourceFilename)) {
			return $this->ErrorImage('"'.$this->sourceFilename.'" does not exist');
		} elseif (!@is_file($this->sourceFilename)) {
			return $this->ErrorImage('"'.$this->sourceFilename.'" is not a file');
		}
		return true;
	}


	function setOutputFormat() {
		static $alreadyCalled = false;
		if ($this->thumbnailFormat && $alreadyCalled) {
			return true;
		}
		$alreadyCalled = true;

		$AvailableImageOutputFormats = array();
		$AvailableImageOutputFormats[] = 'text';
		if (@is_readable(dirname(__FILE__).'/phpthumb.ico.php')) {
			$AvailableImageOutputFormats[] = 'ico';
		}
		if (@is_readable(dirname(__FILE__).'/phpthumb.bmp.php')) {
			$AvailableImageOutputFormats[] = 'bmp';
		}

		$this->thumbnailFormat = 'ico';

		// Set default output format based on what image types are available
		if (function_exists('ImageTypes')) {
			$imagetypes = ImageTypes();
			if ($imagetypes & IMG_WBMP) {
				$this->thumbnailFormat         = 'wbmp';
				$AvailableImageOutputFormats[] = 'wbmp';
			}
			if ($imagetypes & IMG_GIF) {
				$this->thumbnailFormat         = 'gif';
				$AvailableImageOutputFormats[] = 'gif';
			}
			if ($imagetypes & IMG_PNG) {
				$this->thumbnailFormat         = 'png';
				$AvailableImageOutputFormats[] = 'png';
			}
			if ($imagetypes & IMG_JPG) {
				$this->thumbnailFormat         = 'jpeg';
				$AvailableImageOutputFormats[] = 'jpeg';
			}
		} else {
			//return $this->ErrorImage('ImageTypes() does not exist - GD support might not be enabled?');
			$this->DebugMessage('ImageTypes() does not exist - GD support might not be enabled?',  __FILE__, __LINE__);
		}
		if ($this->ImageMagickVersion()) {
			$IMformats = array('jpeg', 'png', 'gif', 'bmp', 'ico', 'wbmp');
			$this->DebugMessage('Addding ImageMagick formats to $AvailableImageOutputFormats ('.implode(';', $AvailableImageOutputFormats).')', __FILE__, __LINE__);
			foreach ($IMformats as $key => $format) {
				$AvailableImageOutputFormats[] = $format;
			}
		}
		$AvailableImageOutputFormats = array_unique($AvailableImageOutputFormats);
		$this->DebugMessage('$AvailableImageOutputFormats = array('.implode(';', $AvailableImageOutputFormats).')', __FILE__, __LINE__);

		$this->f = preg_replace('#[^a-z]#', '', strtolower($this->f));
		if (strtolower($this->config_output_format) == 'jpg') {
			$this->config_output_format = 'jpeg';
		}
		if (strtolower($this->f) == 'jpg') {
			$this->f = 'jpeg';
		}
		if (phpthumb_functions::CaseInsensitiveInArray($this->config_output_format, $AvailableImageOutputFormats)) {
			// set output format to config default if that format is available
			$this->DebugMessage('$this->thumbnailFormat set to $this->config_output_format "'.strtolower($this->config_output_format).'"', __FILE__, __LINE__);
			$this->thumbnailFormat = strtolower($this->config_output_format);
		} elseif ($this->config_output_format) {
			$this->DebugMessage('$this->thumbnailFormat staying as "'.$this->thumbnailFormat.'" because $this->config_output_format ('.strtolower($this->config_output_format).') is not in $AvailableImageOutputFormats', __FILE__, __LINE__);
		}
		if ($this->f && (phpthumb_functions::CaseInsensitiveInArray($this->f, $AvailableImageOutputFormats))) {
			// override output format if $this->f is set and that format is available
			$this->DebugMessage('$this->thumbnailFormat set to $this->f "'.strtolower($this->f).'"', __FILE__, __LINE__);
			$this->thumbnailFormat = strtolower($this->f);
		} elseif ($this->f) {
			$this->DebugMessage('$this->thumbnailFormat staying as "'.$this->thumbnailFormat.'" because $this->f ('.strtolower($this->f).') is not in $AvailableImageOutputFormats', __FILE__, __LINE__);
		}

		// for JPEG images, quality 1 (worst) to 99 (best)
		// quality < 25 is nasty, with not much size savings - not recommended
		// problems with 100 - invalid JPEG?
		$this->thumbnailQuality = max(1, min(99, ($this->q ? intval($this->q) : 75)));
		$this->DebugMessage('$this->thumbnailQuality set to "'.$this->thumbnailQuality.'"', __FILE__, __LINE__);

		return true;
	}


	function setCacheDirectory() {
		// resolve cache directory to absolute pathname
		$this->DebugMessage('setCacheDirectory() starting with config_cache_directory = "'.$this->config_cache_directory.'"', __FILE__, __LINE__);
		if (substr($this->config_cache_directory, 0, 1) == '.') {
			if (preg_match('#^(f|ht)tps?\://#i', $this->src)) {
				if (!$this->config_cache_disable_warning) {
					$this->ErrorImage('$this->config_cache_directory ('.$this->config_cache_directory.') cannot be used for remote images. Adjust "cache_directory" or "cache_disable_warning" in phpThumb.config.php');
				}
			} elseif ($this->src) {
				// resolve relative cache directory to source image
				$this->config_cache_directory = dirname($this->ResolveFilenameToAbsolute($this->src)).DIRECTORY_SEPARATOR.$this->config_cache_directory;
			} else {
				// $this->new is probably set
			}
		}
		if (substr($this->config_cache_directory, -1) == '/') {
			$this->config_cache_directory = substr($this->config_cache_directory, 0, -1);
		}
		if ($this->iswindows) {
			$this->config_cache_directory = str_replace('/', DIRECTORY_SEPARATOR, $this->config_cache_directory);
		}
		if ($this->config_cache_directory) {
			$real_cache_path = realpath($this->config_cache_directory);
			if (!$real_cache_path) {
				$this->DebugMessage('realpath($this->config_cache_directory) failed for "'.$this->config_cache_directory.'"', __FILE__, __LINE__);
				if (!is_dir($this->config_cache_directory)) {
					$this->DebugMessage('!is_dir('.$this->config_cache_directory.')', __FILE__, __LINE__);
				}
			}
			if ($real_cache_path) {
				$this->DebugMessage('setting config_cache_directory to realpath('.$this->config_cache_directory.') = "'.$real_cache_path.'"', __FILE__, __LINE__);
				$this->config_cache_directory = $real_cache_path;
			}
		}
		if (!is_dir($this->config_cache_directory)) {
			if (!$this->config_cache_disable_warning) {
				$this->ErrorImage('$this->config_cache_directory ('.$this->config_cache_directory.') does not exist. Adjust "cache_directory" or "cache_disable_warning" in phpThumb.config.php');
			}
			$this->DebugMessage('$this->config_cache_directory ('.$this->config_cache_directory.') is not a directory', __FILE__, __LINE__);
			$this->config_cache_directory = null;
		} elseif (!@is_writable($this->config_cache_directory)) {
			$this->DebugMessage('$this->config_cache_directory is not writable ('.$this->config_cache_directory.')', __FILE__, __LINE__);
		}

		$this->InitializeTempDirSetting();
		if (!@is_dir($this->config_temp_directory) && !@is_writable($this->config_temp_directory) && @is_dir($this->config_cache_directory) && @is_writable($this->config_cache_directory)) {
			$this->DebugMessage('setting $this->config_temp_directory = $this->config_cache_directory ('.$this->config_cache_directory.')', __FILE__, __LINE__);
			$this->config_temp_directory = $this->config_cache_directory;
		}
		return true;
	}

	/* Takes the array of path segments up to now, and the next segment (maybe a modifier: empty, . or ..)
	   Applies it, adding or removing from $segments as a result. Returns nothing. */
	// http://support.silisoftware.com/phpBB3/viewtopic.php?t=961
	function applyPathSegment(&$segments, $segment) {
		if ($segment == '.') {
			return; // always remove
		}
		if ($segment == '') {
			$test = array_pop($segments);
			if (is_null($test)) {
				$segments[] = $segment; // keep the first empty block
			} elseif ($test == '') {
				$test = array_pop($segments);
				if (is_null($test)) {
					$segments[] = $test;
					$segments[] = $segment; // keep the second one too
				} else { // put both back and ignore segment
					$segments[] = $test;
					$segments[] = $test;
				}
			} else {
				$segments[] = $test; // ignore empty blocks
			}
		} else {
			if ($segment == '..') {
				$test = array_pop($segments);
				if (is_null($test)) {
					$segments[] = $segment;
				} elseif ($test == '..') {
					$segments[] = $test;
					$segments[] = $segment;
				} else {
					if ($test == '') {
						$segments[] = $test;
					} // else nothing, remove both
				}
			} else {
				$segments[] = $segment;
			}
		}
	}

	/* Takes array of path components, normalizes it: removes empty slots and '.', collapses '..' and folder names.  Returns array. */
	// http://support.silisoftware.com/phpBB3/viewtopic.php?t=961
	function normalizePath($segments) {
		$parts = array();
		foreach ($segments as $segment) {
			$this->applyPathSegment($parts, $segment);
		}
		return $parts;
	}

	/* True if the provided path points (without resolving symbolic links) into one of the allowed directories. */
	// http://support.silisoftware.com/phpBB3/viewtopic.php?t=961
	function matchPath($path, $allowed_dirs) {
		if (!empty($allowed_dirs)) {
			foreach ($allowed_dirs as $one_dir) {
				if (preg_match('#^'.preg_quote(str_replace(DIRECTORY_SEPARATOR, '/', realpath($one_dir))).'#', $path)) {
					return true;
				}
			}
		}
		return false;
	}

	/* True if the provided path points inside one of open_basedirs (or if open_basedirs are disabled) */
	// http://support.silisoftware.com/phpBB3/viewtopic.php?t=961
	function isInOpenBasedir($path) {
		static $open_basedirs = null;
		if (is_null($open_basedirs)) {
			$ini_text = ini_get('open_basedir');
			$this->DebugMessage('open_basedir: "'.$ini_text.'"', __FILE__, __LINE__);
			$open_basedirs = array();
			if (strlen($ini_text) > 0) {
				foreach (preg_split('#[;:]#', $ini_text) as $key => $value) {
					$open_basedirs[$key] = realpath($value);
				}
			}
		}
		return (empty($open_basedirs) || $this->matchPath($path, $open_basedirs));
	}

	/* Resolves all symlinks in $path, checking that each continuous part ends in an allowed zone. Returns null, if any component leads outside of allowed zone. */
	// http://support.silisoftware.com/phpBB3/viewtopic.php?t=961
	function resolvePath($path, $allowed_dirs) {
		$this->DebugMessage('resolvePath: '.$path.' (allowed_dirs: '.print_r($allowed_dirs, true).')', __FILE__, __LINE__);

		// add base path to the top of the list
		if (!$this->config_allow_src_above_docroot) {
			array_unshift($allowed_dirs, realpath($this->config_document_root));
		} else {
			if (!$this->config_allow_src_above_phpthumb) {
				array_unshift($allowed_dirs, realpath(dirname(__FILE__)));
			} else {
				// no checks are needed, offload the work to realpath and forget about it
				$this->DebugMessage('resolvePath: checks disabled, returning '.realpath($path), __FILE__, __LINE__);
				return realpath($path);
			}
		}
		if ($path == '') {
			return null; // save us trouble
		}

		do {
			$this->DebugMessage('resolvePath: iteration, path='.$path.', base path = '.$allowed_dirs[0], __FILE__, __LINE__);

			$parts = array();
			// do not use "cleaner" foreach version of this loop as later code relies on both $segments and $i
			// http://support.silisoftware.com/phpBB3/viewtopic.php?t=964
			$segments = explode(DIRECTORY_SEPARATOR, $path);
			for ($i = 0; $i < count($segments); $i++) {
	            $this->applyPathSegment($parts, $segments[$i]);
				$thispart = implode(DIRECTORY_SEPARATOR, $parts);
				if ($this->isInOpenBasedir($thispart)) {
					if (is_link($thispart)) {
						break;
					}
				}
			}

			$this->DebugMessage('resolvePath: stop at component '.$i, __FILE__, __LINE__);
			// test the part up to here
			$path = implode(DIRECTORY_SEPARATOR, $parts);
			$this->DebugMessage('resolvePath: stop at path='.$path, __FILE__, __LINE__);
			if (!$this->matchPath($path, $allowed_dirs)) {
				$this->DebugMessage('resolvePath: no match, returning null', __FILE__, __LINE__);
				return null;
			}
			if ($i >= count($segments)) { // reached end
				$this->DebugMessage('resolvePath: path parsed, over', __FILE__, __LINE__);
				break;
			}
			// else it's symlink, rewrite path
			$path = readlink($path);
			$this->DebugMessage('resolvePath: symlink matched, target='.$path, __FILE__, __LINE__);

			/*
			Replace base path with symlink target.
			Assuming:
			  /www/img/external -> /external
			This is allowed:
			  GET /www/img/external/../external/test/pic.jpg
			This isn't:
			  GET /www/img/external/../www/img/pic.jpg
			So there's only one base path which is the last symlink target, but any number of stable whitelisted paths.
			*/
			if ($this->config_auto_allow_symlinks) {
				$allowed_dirs[0] = $path;
			}
			$path = $path.DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, array_slice($segments,$i + 1));
		} while (true);
		return $path;
	}

	function ResolveFilenameToAbsolute($filename) {
		if (empty($filename)) {
			return false;
		}

		if (preg_match('#^[a-z0-9]+\:/{1,2}#i', $filename)) {
			// eg: http://host/path/file.jpg (HTTP URL)
			// eg: ftp://host/path/file.jpg  (FTP URL)
			// eg: data1:/path/file.jpg      (Netware path)

			//$AbsoluteFilename = $filename;
			return $filename;

		} elseif ($this->iswindows && isset($filename{1}) && ($filename{1} == ':')) {

			// absolute pathname (Windows)
			$AbsoluteFilename = $filename;

		} elseif ($this->iswindows && ((substr($filename, 0, 2) == '//') || (substr($filename, 0, 2) == '\\\\'))) {

			// absolute pathname (Windows)
			$AbsoluteFilename = $filename;

		} elseif ($filename{0} == '/') {

			if (@is_readable($filename) && !@is_readable($this->config_document_root.$filename)) {

				// absolute filename (*nix)
				$AbsoluteFilename = $filename;

			} elseif (isset($filename{1}) && ($filename{1} == '~')) {

				// /~user/path
				if ($ApacheLookupURIarray = phpthumb_functions::ApacheLookupURIarray($filename)) {
					$AbsoluteFilename = $ApacheLookupURIarray['filename'];
				} else {
					$AbsoluteFilename = realpath($filename);
					if (@is_readable($AbsoluteFilename)) {
						$this->DebugMessage('phpthumb_functions::ApacheLookupURIarray() failed for "'.$filename.'", but the correct filename ('.$AbsoluteFilename.') seems to have been resolved with realpath($filename)', __FILE__, __LINE__);
					} elseif (is_dir(dirname($AbsoluteFilename))) {
						$this->DebugMessage('phpthumb_functions::ApacheLookupURIarray() failed for "'.dirname($filename).'", but the correct directory ('.dirname($AbsoluteFilename).') seems to have been resolved with realpath(.)', __FILE__, __LINE__);
					} else {
						return $this->ErrorImage('phpthumb_functions::ApacheLookupURIarray() failed for "'.$filename.'". This has been known to fail on Apache2 - try using the absolute filename for the source image (ex: "/home/user/httpdocs/image.jpg" instead of "/~user/image.jpg")');
					}
				}

			} else {

				// relative filename (any OS)
				if (preg_match('#^'.preg_quote($this->config_document_root).'#', $filename)) {
					$AbsoluteFilename = $filename;
					$this->DebugMessage('ResolveFilenameToAbsolute() NOT prepending $this->config_document_root ('.$this->config_document_root.') to $filename ('.$filename.') resulting in ($AbsoluteFilename = "'.$AbsoluteFilename.'")', __FILE__, __LINE__);
				} else {
					$AbsoluteFilename = $this->config_document_root.$filename;
					$this->DebugMessage('ResolveFilenameToAbsolute() prepending $this->config_document_root ('.$this->config_document_root.') to $filename ('.$filename.') resulting in ($AbsoluteFilename = "'.$AbsoluteFilename.'")', __FILE__, __LINE__);
				}

			}

		} else {

			// relative to current directory (any OS)
			$AbsoluteFilename = dirname(__FILE__).DIRECTORY_SEPARATOR.preg_replace('#[/\\\\]#', DIRECTORY_SEPARATOR, $filename);

			if (substr(dirname(@$_SERVER['PHP_SELF']), 0, 2) == '/~') {
				if ($ApacheLookupURIarray = phpthumb_functions::ApacheLookupURIarray(dirname(@$_SERVER['PHP_SELF']))) {
					$AbsoluteFilename = $ApacheLookupURIarray['filename'].DIRECTORY_SEPARATOR.$filename;
				} else {
					$AbsoluteFilename = realpath('.').DIRECTORY_SEPARATOR.$filename;
					if (@is_readable($AbsoluteFilename)) {
						$this->DebugMessage('phpthumb_functions::ApacheLookupURIarray() failed for "'.dirname(@$_SERVER['PHP_SELF']).'", but the correct filename ('.$AbsoluteFilename.') seems to have been resolved with realpath(.)/$filename', __FILE__, __LINE__);
					} elseif (is_dir(dirname($AbsoluteFilename))) {
						$this->DebugMessage('phpthumb_functions::ApacheLookupURIarray() failed for "'.dirname(@$_SERVER['PHP_SELF']).'", but the correct directory ('.dirname($AbsoluteFilename).') seems to have been resolved with realpath(.)', __FILE__, __LINE__);
					} else {
						return $this->ErrorImage('phpthumb_functions::ApacheLookupURIarray() failed for "'.dirname(@$_SERVER['PHP_SELF']).'". This has been known to fail on Apache2 - try using the absolute filename for the source image');
					}
				}
			}

		}
		/*
		// removed 2014-May-30: http://support.silisoftware.com/phpBB3/viewtopic.php?t=961
		if (is_link($AbsoluteFilename)) {
			$this->DebugMessage('is_link()==true, changing "'.$AbsoluteFilename.'" to "'.readlink($AbsoluteFilename).'"', __FILE__, __LINE__);
			$AbsoluteFilename = readlink($AbsoluteFilename);
		}
		if (realpath($AbsoluteFilename)) {
			$AbsoluteFilename = realpath($AbsoluteFilename);
		}
		*/
		if ($this->iswindows) {
			$AbsoluteFilename = preg_replace('#^'.preg_quote(realpath($this->config_document_root)).'#i', realpath($this->config_document_root), $AbsoluteFilename);
			$AbsoluteFilename = str_replace(DIRECTORY_SEPARATOR, '/', $AbsoluteFilename);
		}
		$AbsoluteFilename = $this->resolvePath($AbsoluteFilename, $this->config_additional_allowed_dirs);
		if (!$this->config_allow_src_above_docroot && !preg_match('#^'.preg_quote(str_replace(DIRECTORY_SEPARATOR, '/', realpath($this->config_document_root))).'#', $AbsoluteFilename)) {
			$this->DebugMessage('!$this->config_allow_src_above_docroot therefore setting "'.$AbsoluteFilename.'" (outside "'.realpath($this->config_document_root).'") to null', __FILE__, __LINE__);
			return false;
		}
		if (!$this->config_allow_src_above_phpthumb && !preg_match('#^'.preg_quote(str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__))).'#', $AbsoluteFilename)) {
			$this->DebugMessage('!$this->config_allow_src_above_phpthumb therefore setting "'.$AbsoluteFilename.'" (outside "'.dirname(__FILE__).'") to null', __FILE__, __LINE__);
			return false;
		}
		return $AbsoluteFilename;
	}


	function file_exists_ignoreopenbasedir($filename, $cached=true) {
		static $open_basedirs = null;
		static $file_exists_cache = array();
		if (!$cached || !isset($file_exists_cache[$filename])) {
			if (is_null($open_basedirs)) {
				$open_basedirs = preg_split('#[;:]#', ini_get('open_basedir'));
			}
			if (empty($open_basedirs) || in_array(dirname($filename), $open_basedirs)) {
				$file_exists_cache[$filename] = file_exists($filename);
			} elseif ($this->iswindows) {
				$ls_filename = trim(phpthumb_functions::SafeExec('dir '.escapeshellarg($filename)));
				$file_exists_cache[$filename] = !preg_match('#File Not Found#i', $ls_filename);
			} else {
				$ls_filename = trim(phpthumb_functions::SafeExec('ls '.escapeshellarg($filename)));
				$file_exists_cache[$filename] = ($ls_filename == $filename);
			}
		}
		return $file_exists_cache[$filename];
	}


	function ImageMagickWhichConvert() {
		static $WhichConvert = null;
		if (is_null($WhichConvert)) {
			if ($this->iswindows) {
				$WhichConvert = false;
			} else {
				$IMwhichConvertCacheFilename = $this->config_cache_directory.DIRECTORY_SEPARATOR.'phpThumbCacheIMwhichConvert.txt';
				if (($cachedwhichconvertstring = @file_get_contents($IMwhichConvertCacheFilename)) !== false) {
					$WhichConvert = $cachedwhichconvertstring;
				} else {
					$WhichConvert = trim(phpthumb_functions::SafeExec('which convert'));
					@file_put_contents($IMwhichConvertCacheFilename, $WhichConvert);
				}
			}
		}
		return $WhichConvert;
	}


	function ImageMagickCommandlineBase() {
		static $commandline = null;
		if (is_null($commandline)) {
			if ($this->issafemode) {
				$commandline = '';
				return $commandline;
			}

			$IMcommandlineBaseCacheFilename = $this->config_cache_directory.DIRECTORY_SEPARATOR.'phpThumbCacheIMcommandlineBase.txt';
			if (($commandline = @file_get_contents($IMcommandlineBaseCacheFilename)) !== false) {
				return $commandline;
			}

			$commandline = (!is_null($this->config_imagemagick_path) ? $this->config_imagemagick_path : '');

			if ($this->config_imagemagick_path && ($this->config_imagemagick_path != realpath($this->config_imagemagick_path))) {
				if (@is_executable(realpath($this->config_imagemagick_path))) {
					$this->DebugMessage('Changing $this->config_imagemagick_path ('.$this->config_imagemagick_path.') to realpath($this->config_imagemagick_path) ('.realpath($this->config_imagemagick_path).')', __FILE__, __LINE__);
					$this->config_imagemagick_path = realpath($this->config_imagemagick_path);
				} else {
					$this->DebugMessage('Leaving $this->config_imagemagick_path as ('.$this->config_imagemagick_path.') because !is_execuatable(realpath($this->config_imagemagick_path)) ('.realpath($this->config_imagemagick_path).')', __FILE__, __LINE__);
				}
			}
			$this->DebugMessage('                  file_exists('.$this->config_imagemagick_path.') = '.intval(                        @file_exists($this->config_imagemagick_path)), __FILE__, __LINE__);
			$this->DebugMessage('file_exists_ignoreopenbasedir('.$this->config_imagemagick_path.') = '.intval($this->file_exists_ignoreopenbasedir($this->config_imagemagick_path)), __FILE__, __LINE__);
			$this->DebugMessage('                      is_file('.$this->config_imagemagick_path.') = '.intval(                            @is_file($this->config_imagemagick_path)), __FILE__, __LINE__);
			$this->DebugMessage('                is_executable('.$this->config_imagemagick_path.') = '.intval(                      @is_executable($this->config_imagemagick_path)), __FILE__, __LINE__);

			if ($this->file_exists_ignoreopenbasedir($this->config_imagemagick_path)) {

				$this->DebugMessage('using ImageMagick path from $this->config_imagemagick_path ('.$this->config_imagemagick_path.')', __FILE__, __LINE__);
				if ($this->iswindows) {
					$commandline = substr($this->config_imagemagick_path, 0, 2).' && cd '.escapeshellarg(str_replace('/', DIRECTORY_SEPARATOR, substr(dirname($this->config_imagemagick_path), 2))).' && '.escapeshellarg(basename($this->config_imagemagick_path));
				} else {
					$commandline = escapeshellarg($this->config_imagemagick_path);
				}

			} else {

				$which_convert = $this->ImageMagickWhichConvert();
				$IMversion     = $this->ImageMagickVersion();

				if ($which_convert && ($which_convert{0} == '/') && $this->file_exists_ignoreopenbasedir($which_convert)) {

					// `which convert` *should* return the path if "convert" exist, or nothing if it doesn't
					// other things *may* get returned, like "sh: convert: not found" or "no convert in /usr/local/bin /usr/sbin /usr/bin /usr/ccs/bin"
					// so only do this if the value returned exists as a file
					$this->DebugMessage('using ImageMagick path from `which convert` ('.$which_convert.')', __FILE__, __LINE__);
					$commandline = 'convert';

				} elseif ($IMversion) {

					$this->DebugMessage('setting ImageMagick path to $this->config_imagemagick_path ('.$this->config_imagemagick_path.') ['.$IMversion.']', __FILE__, __LINE__);
					$commandline = $this->config_imagemagick_path;

				} else {

					$this->DebugMessage('ImageMagickThumbnailToGD() aborting because cannot find convert in $this->config_imagemagick_path ('.$this->config_imagemagick_path.'), and `which convert` returned ('.$which_convert.')', __FILE__, __LINE__);
					$commandline = '';

				}

			}

			@file_put_contents($IMcommandlineBaseCacheFilename, $commandline);
		}
		return $commandline;
	}


	function ImageMagickVersion($returnRAW=false) {
		static $versionstring = null;
		if (is_null($versionstring)) {
			$versionstring = array(0=>false, 1=>false);

			$IMversionCacheFilename = $this->config_cache_directory.DIRECTORY_SEPARATOR.'phpThumbCacheIMversion.txt';
			if ($cachedversionstring = @file_get_contents($IMversionCacheFilename)) {

				$versionstring = explode("\n", $cachedversionstring, 2);
				$versionstring[0] = ($versionstring[0] ? $versionstring[0] : false); // "false" is stored as an empty string in the cache file
				$versionstring[1] = ($versionstring[1] ? $versionstring[1] : false); // "false" is stored as an empty string in the cache file

			} else {

				$commandline = $this->ImageMagickCommandlineBase();
				$commandline = (!is_null($commandline) ? $commandline : '');
				if ($commandline) {
					$commandline .= ' --version';
					$this->DebugMessage('ImageMagick version checked with "'.$commandline.'"', __FILE__, __LINE__);
					$versionstring[1] = trim(phpthumb_functions::SafeExec($commandline));
					if (preg_match('#^Version: [^0-9]*([ 0-9\\.\\:Q/\\-]+)#i', $versionstring[1], $matches)) {
						$versionstring[0] = $matches[1];
					} else {
						$versionstring[0] = false;
						$this->DebugMessage('ImageMagick did not return recognized version string ('.$versionstring[1].')', __FILE__, __LINE__);
					}
					$this->DebugMessage('ImageMagick convert --version says "'.@$matches[0].'"', __FILE__, __LINE__);
				}

				@file_put_contents($IMversionCacheFilename, $versionstring[0]."\n".$versionstring[1]);

			}
		}
		return $versionstring[intval($returnRAW)];
	}


	function ImageMagickSwitchAvailable($switchname) {
		static $IMoptions = null;
		if (is_null($IMoptions)) {
			$IMoptions = array();
			$commandline = $this->ImageMagickCommandlineBase();
			if (!is_null($commandline)) {
				$commandline .= ' -help';
				$IMhelp_lines = explode("\n", phpthumb_functions::SafeExec($commandline));
				foreach ($IMhelp_lines as $line) {
					if (preg_match('#^[\\+\\-]([a-z\\-]+) #', trim($line), $matches)) {
						$IMoptions[$matches[1]] = true;
					}
				}
			}
		}
		if (is_array($switchname)) {
			$allOK = true;
			foreach ($switchname as $key => $value) {
				if (!isset($IMoptions[$value])) {
					$allOK = false;
					break;
				}
			}
			$this->DebugMessage('ImageMagickSwitchAvailable('.implode(';', $switchname).') = '.intval($allOK).'', __FILE__, __LINE__);
		} else {
			$allOK = isset($IMoptions[$switchname]);
			$this->DebugMessage('ImageMagickSwitchAvailable('.$switchname.') = '.intval($allOK).'', __FILE__, __LINE__);
		}
		return $allOK;
	}


	function ImageMagickFormatsList() {
		static $IMformatsList = null;
		if (is_null($IMformatsList)) {
			$IMformatsList = '';
			$commandline = $this->ImageMagickCommandlineBase();
			if (!is_null($commandline)) {
				$commandline = dirname($commandline).DIRECTORY_SEPARATOR.str_replace('convert', 'identify', basename($commandline));
				$commandline .= ' -list format';
				$IMformatsList = phpthumb_functions::SafeExec($commandline);
			}
		}
		return $IMformatsList;
	}


	function SourceDataToTempFile() {
		if ($IMtempSourceFilename = $this->phpThumb_tempnam()) {
			$IMtempSourceFilename = realpath($IMtempSourceFilename);
			ob_start();
			$fp_tempfile = fopen($IMtempSourceFilename, 'wb');
			$tempfile_open_error  = ob_get_contents();
			ob_end_clean();
			if ($fp_tempfile) {
				fwrite($fp_tempfile, $this->rawImageData);
				fclose($fp_tempfile);
				$this->sourceFilename = $IMtempSourceFilename;
				$this->DebugMessage('ImageMagickThumbnailToGD() setting $this->sourceFilename to "'.$IMtempSourceFilename.'" from $this->rawImageData ('.strlen($this->rawImageData).' bytes)', __FILE__, __LINE__);
			} else {
				$this->DebugMessage('ImageMagickThumbnailToGD() FAILED setting $this->sourceFilename to "'.$IMtempSourceFilename.'" (failed to open for writing: "'.$tempfile_open_error.'")', __FILE__, __LINE__);
			}
			unset($tempfile_open_error, $IMtempSourceFilename);
			return true;
		}
		$this->DebugMessage('SourceDataToTempFile() FAILED because $this->phpThumb_tempnam() failed', __FILE__, __LINE__);
		return false;
	}


	function ImageMagickThumbnailToGD() {
		// http://www.imagemagick.org/script/command-line-options.php

		$this->useRawIMoutput = true;
		if (phpthumb_functions::gd_version()) {
			// if GD is not available, must use whatever ImageMagick can output

			// $UnAllowedParameters contains options that can only be processed in GD, not ImageMagick
			// note: 'fltr' *may* need to be processed by GD, but we'll check that in more detail below
			$UnAllowedParameters = array('xto', 'ar', 'bg', 'bc');
			// 'ra' may be part of this list, if not a multiple of 90�
			foreach ($UnAllowedParameters as $parameter) {
				if (isset($this->$parameter)) {
					$this->DebugMessage('$this->useRawIMoutput=false because "'.$parameter.'" is set', __FILE__, __LINE__);
					$this->useRawIMoutput = false;
					break;
				}
			}
		}
		$this->DebugMessage('$this->useRawIMoutput='.($this->useRawIMoutput ? 'true' : 'false').' after checking $UnAllowedParameters', __FILE__, __LINE__);
		$outputFormat = $this->thumbnailFormat;
		if (phpthumb_functions::gd_version()) {
			if ($this->useRawIMoutput) {
				switch ($this->thumbnailFormat) {
					case 'gif':
						$ImageCreateFunction = 'ImageCreateFromGIF';
						$this->is_alpha = true;
						break;
					case 'png':
						$ImageCreateFunction = 'ImageCreateFromPNG';
						$this->is_alpha = true;
						break;
					case 'jpg':
					case 'jpeg':
						$ImageCreateFunction = 'ImageCreateFromJPEG';
						break;
					default:
						$this->DebugMessage('Forcing output to PNG because $this->thumbnailFormat ('.$this->thumbnailFormat.' is not a GD-supported format)', __FILE__, __LINE__);
						$outputFormat = 'png';
						$ImageCreateFunction = 'ImageCreateFromPNG';
						$this->is_alpha = true;
						$this->useRawIMoutput = false;
						break;
				}
				if (!function_exists(@$ImageCreateFunction)) {
					// ImageMagickThumbnailToGD() depends on ImageCreateFromPNG/ImageCreateFromGIF
					//$this->DebugMessage('ImageMagickThumbnailToGD() aborting because '.@$ImageCreateFunction.'() is not available', __FILE__, __LINE__);
					$this->useRawIMoutput = true;
					//return false;
				}
			} else {
				$outputFormat = 'png';
				$ImageCreateFunction = 'ImageCreateFromPNG';
				$this->is_alpha = true;
				$this->useRawIMoutput = false;
			}
		}

		// http://freealter.org/doc_distrib/ImageMagick-5.1.1/www/convert.html
		if (!$this->sourceFilename && $this->rawImageData) {
			$this->SourceDataToTempFile();
		}
		if (!$this->sourceFilename) {
			$this->DebugMessage('ImageMagickThumbnailToGD() aborting because $this->sourceFilename is empty', __FILE__, __LINE__);
			$this->useRawIMoutput = false;
			return false;
		}
		if ($this->issafemode) {
			$this->DebugMessage('ImageMagickThumbnailToGD() aborting because safe_mode is enabled', __FILE__, __LINE__);
			$this->useRawIMoutput = false;
			return false;
		}
// TO BE FIXED
//if (true) {
//	$this->DebugMessage('ImageMagickThumbnailToGD() aborting it is broken right now', __FILE__, __LINE__);
//	$this->useRawIMoutput = false;
//	return false;
//}

		$commandline = $this->ImageMagickCommandlineBase();
		if ($commandline) {
			if ($IMtempfilename = $this->phpThumb_tempnam()) {
				$IMtempfilename = realpath($IMtempfilename);

				$IMuseExplicitImageOutputDimensions = false;
				if ($this->ImageMagickSwitchAvailable('thumbnail') && $this->config_imagemagick_use_thumbnail) {
					$IMresizeParameter = 'thumbnail';
				} else {
					$IMresizeParameter = 'resize';

					// some (older? around 2002) versions of IM won't accept "-resize 100x" but require "-resize 100x100"
					$commandline_test = $this->ImageMagickCommandlineBase().' logo: -resize 1x '.escapeshellarg($IMtempfilename).' 2>&1';
					$IMresult_test = phpthumb_functions::SafeExec($commandline_test);
					$IMuseExplicitImageOutputDimensions = preg_match('#image dimensions are zero#i', $IMresult_test);
					$this->DebugMessage('IMuseExplicitImageOutputDimensions = '.intval($IMuseExplicitImageOutputDimensions), __FILE__, __LINE__);
					if ($fp_im_temp = @fopen($IMtempfilename, 'wb')) {
						// erase temp image so ImageMagick logo doesn't get output if other processing fails
						fclose($fp_im_temp);
					}
				}


				if (!is_null($this->dpi) && $this->ImageMagickSwitchAvailable('density')) {
					// for raster source formats only (WMF, PDF, etc)
					$commandline .= ' -density '.escapeshellarg($this->dpi);
				}
				ob_start();
				$getimagesize = GetImageSize($this->sourceFilename);
				$GetImageSizeError = ob_get_contents();
				ob_end_clean();
				if (is_array($getimagesize)) {
					$this->DebugMessage('GetImageSize('.$this->sourceFilename.') SUCCEEDED: '.print_r($getimagesize, true), __FILE__, __LINE__);
				} else {
					$this->DebugMessage('GetImageSize('.$this->sourceFilename.') FAILED with error "'.$GetImageSizeError.'"', __FILE__, __LINE__);
				}
				if (is_array($getimagesize)) {
					$this->DebugMessage('GetImageSize('.$this->sourceFilename.') returned [w='.$getimagesize[0].';h='.$getimagesize[1].';f='.$getimagesize[2].']', __FILE__, __LINE__);
					$this->source_width  = $getimagesize[0];
					$this->source_height = $getimagesize[1];
					$this->DebugMessage('source dimensions set to '.$this->source_width.'x'.$this->source_height, __FILE__, __LINE__);
					$this->SetOrientationDependantWidthHeight();

					if (!preg_match('#('.implode('|', $this->AlphaCapableFormats).')#i', $outputFormat)) {
						// not a transparency-capable format
						$commandline .= ' -background '.escapeshellarg('#'.($this->bg ? $this->bg : 'FFFFFF'));
						if ($getimagesize[2] == IMAGETYPE_GIF) {
							$commandline .= ' -flatten';
						}
					}
					if ($getimagesize[2] == IMAGETYPE_GIF) {
						$commandline .= ' -coalesce'; // may be needed for animated GIFs
					}
					if ($this->source_width || $this->source_height) {
						if ($this->zc) {

							$borderThickness = 0;
							if (!empty($this->fltr)) {
								foreach ($this->fltr as $key => $value) {
									if (preg_match('#^bord\|([0-9]+)#', $value, $matches)) {
										$borderThickness = $matches[1];
										break;
									}
								}
							}
							$wAll = intval(max($this->w, $this->wp, $this->wl, $this->ws)) - (2 * $borderThickness);
							$hAll = intval(max($this->h, $this->hp, $this->hl, $this->hs)) - (2 * $borderThickness);
							$imAR = $this->source_width / $this->source_height;
							$zcAR = (($wAll && $hAll) ? $wAll / $hAll : 1);
							$side  = phpthumb_functions::nonempty_min($this->source_width, $this->source_height, max($wAll, $hAll));
							$sideX = phpthumb_functions::nonempty_min($this->source_width,                       $wAll, round($hAll * $zcAR));
							$sideY = phpthumb_functions::nonempty_min(                     $this->source_height, $hAll, round($wAll / $zcAR));

							$thumbnailH = round(max($sideY, ($sideY * $zcAR) / $imAR));
							$commandline .= ' -'.$IMresizeParameter.' '.escapeshellarg(($IMuseExplicitImageOutputDimensions ? $thumbnailH : '').'x'.$thumbnailH);

							switch (strtoupper($this->zc)) {
								case 'T':
									$commandline .= ' -gravity north';
									break;
								case 'B':
									$commandline .= ' -gravity south';
									break;
								case 'L':
									$commandline .= ' -gravity west';
									break;
								case 'R':
									$commandline .= ' -gravity east';
									break;
								case 'TL':
									$commandline .= ' -gravity northwest';
									break;
								case 'TR':
									$commandline .= ' -gravity northeast';
									break;
								case 'BL':
									$commandline .= ' -gravity southwest';
									break;
								case 'BR':
									$commandline .= ' -gravity southeast';
									break;
								case '1':
								case 'C':
								default:
									$commandline .= ' -gravity center';
									break;
							}

							if (($wAll > 0) && ($hAll > 0)) {
								$commandline .= ' -crop '.escapeshellarg($wAll.'x'.$hAll.'+0+0');
							} else {
								$commandline .= ' -crop '.escapeshellarg($side.'x'.$side.'+0+0');
							}
							if ($this->ImageMagickSwitchAvailable('repage')) {
								$commandline .= ' +repage';
							} else {
								$this->DebugMessage('Skipping "+repage" because ImageMagick (v'.$this->ImageMagickVersion().') does not support it', __FILE__, __LINE__);
							}

						} elseif ($this->sw || $this->sh || $this->sx || $this->sy) {

							$crop_param   = '';
							$crop_param  .=     ($this->sw ? (($this->sw < 2) ? round($this->sw * $this->source_width)  : $this->sw) : $this->source_width);
							$crop_param  .= 'x'.($this->sh ? (($this->sh < 2) ? round($this->sh * $this->source_height) : $this->sh) : $this->source_height);
							$crop_param  .= '+'.(($this->sx < 2) ? round($this->sx * $this->source_width)  : $this->sx);
							$crop_param  .= '+'.(($this->sy < 2) ? round($this->sy * $this->source_height) : $this->sy);
// TO BE FIXED
// makes 1x1 output
// http://trainspotted.com/phpThumb/phpThumb.php?src=/content/CNR/47/CNR-4728-LD-L-20110723-898.jpg&w=100&h=100&far=1&f=png&fltr[]=lvl&sx=0.05&sy=0.25&sw=0.92&sh=0.42
// '/usr/bin/convert' -density 150 -thumbnail 100x100 -contrast-stretch '0.1%' '/var/www/vhosts/trainspotted.com/httpdocs/content/CNR/47/CNR-4728-LD-L-20110723-898.jpg[0]' png:'/var/www/vhosts/trainspotted.com/httpdocs/phpThumb/_cache/pThumbIIUlvj'
							$commandline .= ' -crop '.escapeshellarg($crop_param);

							// this is broken for aoe=1, but unsure how to fix. Send advice to info@silisoftware.com
							if ($this->w || $this->h) {
								//if ($this->ImageMagickSwitchAvailable('repage')) {
if (false) {
// TO BE FIXED
// newer versions of ImageMagick require -repage <geometry>
									$commandline .= ' -repage';
								} else {
									$this->DebugMessage('Skipping "-repage" because ImageMagick (v'.$this->ImageMagickVersion().') does not support it', __FILE__, __LINE__);
								}
								if ($IMuseExplicitImageOutputDimensions) {
									if ($this->w && !$this->h) {
										$this->h = ceil($this->w / ($this->source_width / $this->source_height));
									} elseif ($this->h && !$this->w) {
										$this->w = ceil($this->h * ($this->source_width / $this->source_height));
									}
								}
								$commandline .= ' -'.$IMresizeParameter.' '.escapeshellarg($this->w.'x'.$this->h);
							}

						} else {

							if ($this->iar && (intval($this->w) > 0) && (intval($this->h) > 0)) {
								list($nw, $nh) = phpthumb_functions::TranslateWHbyAngle($this->w, $this->h, $this->ra);
								$nw = ((round($nw) != 0) ? round($nw) : '');
								$nh = ((round($nh) != 0) ? round($nh) : '');
								$commandline .= ' -'.$IMresizeParameter.' '.escapeshellarg($nw.'x'.$nh.'!');
							} else {
								$this->w = ((($this->aoe || $this->far) && $this->w) ? $this->w : ($this->w ? phpthumb_functions::nonempty_min($this->w, $getimagesize[0]) : ''));
								$this->h = ((($this->aoe || $this->far) && $this->h) ? $this->h : ($this->h ? phpthumb_functions::nonempty_min($this->h, $getimagesize[1]) : ''));
								if ($this->w || $this->h) {
									if ($IMuseExplicitImageOutputDimensions) {
										if ($this->w && !$this->h) {
											$this->h = ceil($this->w / ($this->source_width / $this->source_height));
										} elseif ($this->h && !$this->w) {
											$this->w = ceil($this->h * ($this->source_width / $this->source_height));
										}
									}
									list($nw, $nh) = phpthumb_functions::TranslateWHbyAngle($this->w, $this->h, $this->ra);
									$nw = ((round($nw) != 0) ? round($nw) : '');
									$nh = ((round($nh) != 0) ? round($nh) : '');
									$commandline .= ' -'.$IMresizeParameter.' '.escapeshellarg($nw.'x'.$nh);
								}
							}
						}
					}

				} else {

					$this->DebugMessage('GetImageSize('.$this->sourceFilename.') failed', __FILE__, __LINE__);
					if ($this->w || $this->h) {
						$exactDimensionsBang = (($this->iar && (intval($this->w) > 0) && (intval($this->h) > 0)) ? '!' : '');
						if ($IMuseExplicitImageOutputDimensions) {
							// unknown source aspect ratio, just put large number and hope IM figures it out
							$commandline .= ' -'.$IMresizeParameter.' '.escapeshellarg(($this->w ? $this->w : '9999').'x'.($this->h ? $this->h : '9999').$exactDimensionsBang);
						} else {
							$commandline .= ' -'.$IMresizeParameter.' '.escapeshellarg($this->w.'x'.$this->h.$exactDimensionsBang);
						}
					}

				}

				if ($this->ra) {
					$this->ra = intval($this->ra);
					if ($this->ImageMagickSwitchAvailable('rotate')) {
						if (!preg_match('#('.implode('|', $this->AlphaCapableFormats).')#i', $outputFormat) || phpthumb_functions::version_compare_replacement($this->ImageMagickVersion(), '6.3.7', '>=')) {
							$this->DebugMessage('Using ImageMagick rotate', __FILE__, __LINE__);
							$commandline .= ' -rotate '.escapeshellarg($this->ra);
							if (($this->ra % 90) != 0) {
								if (preg_match('#('.implode('|', $this->AlphaCapableFormats).')#i', $outputFormat)) {
									// alpha-capable format
									$commandline .= ' -background rgba(255,255,255,0)';
								} else {
									$commandline .= ' -background '.escapeshellarg('#'.($this->bg ? $this->bg : 'FFFFFF'));
								}
							}
							$this->ra = 0;
						} else {
							$this->DebugMessage('Not using ImageMagick rotate because alpha background buggy before v6.3.7', __FILE__, __LINE__);
						}
					} else {
						$this->DebugMessage('Not using ImageMagick rotate because not supported', __FILE__, __LINE__);
					}
				}

				$successfullyProcessedFilters = array();
				foreach ($this->fltr as $filterkey => $filtercommand) {
					@list($command, $parameter) = explode('|', $filtercommand, 2);
					switch ($command) {
						case 'brit':
							if ($this->ImageMagickSwitchAvailable('modulate')) {
								$commandline .= ' -modulate '.escapeshellarg((100 + intval($parameter)).',100,100');
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'cont':
							if ($this->ImageMagickSwitchAvailable('contrast')) {
								$contDiv10 = round(intval($parameter) / 10);
								if ($contDiv10 > 0) {
									$contDiv10 = min($contDiv10, 100);
									for ($i = 0; $i < $contDiv10; $i++) {
										$commandline .= ' -contrast'; // increase contrast by 10%
									}
								} elseif ($contDiv10 < 0) {
									$contDiv10 = max($contDiv10, -100);
									for ($i = $contDiv10; $i < 0; $i++) {
										$commandline .= ' +contrast'; // decrease contrast by 10%
									}
								} else {
									// do nothing
								}
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'ds':
							if ($this->ImageMagickSwitchAvailable(array('colorspace', 'modulate'))) {
								if ($parameter == 100) {
									$commandline .= ' -colorspace GRAY';
									$commandline .= ' -modulate 100,0,100';
								} else {
									$commandline .= ' -modulate '.escapeshellarg('100,'.(100 - intval($parameter)).',100');
								}
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'sat':
							if ($this->ImageMagickSwitchAvailable(array('colorspace', 'modulate'))) {
								if ($parameter == -100) {
									$commandline .= ' -colorspace GRAY';
									$commandline .= ' -modulate 100,0,100';
								} else {
									$commandline .= ' -modulate '.escapeshellarg('100,'.(100 + intval($parameter)).',100');
								}
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'gray':
							if ($this->ImageMagickSwitchAvailable(array('colorspace', 'modulate'))) {
								$commandline .= ' -colorspace GRAY';
								$commandline .= ' -modulate 100,0,100';
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'clr':
							if ($this->ImageMagickSwitchAvailable(array('fill', 'colorize'))) {
								@list($amount, $color) = explode('|', $parameter);
								$commandline .= ' -fill '.escapeshellarg('#'.preg_replace('#[^0-9A-F]#i', '', $color));
								$commandline .= ' -colorize '.escapeshellarg(min(max(intval($amount), 0), 100));
							}
							break;

						case 'sep':
							if ($this->ImageMagickSwitchAvailable('sepia-tone')) {
								@list($amount, $color) = explode('|', $parameter);
								$amount = ($amount ? $amount : 80);
								if (!$color) {
									$commandline .= ' -sepia-tone '.escapeshellarg(min(max(intval($amount), 0), 100).'%');
									$successfullyProcessedFilters[] = $filterkey;
								}
							}
							break;

						case 'gam':
							@list($amount) = explode('|', $parameter);
							$amount = min(max(floatval($amount), 0.001), 10);
							if (number_format($amount, 3) != '1.000') {
								if ($this->ImageMagickSwitchAvailable('gamma')) {
									$commandline .= ' -gamma '.escapeshellarg($amount);
									$successfullyProcessedFilters[] = $filterkey;
								}
							}
							break;

						case 'neg':
							if ($this->ImageMagickSwitchAvailable('negate')) {
								$commandline .= ' -negate';
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'th':
							@list($amount) = explode('|', $parameter);
							if ($this->ImageMagickSwitchAvailable(array('threshold', 'dither', 'monochrome'))) {
								$commandline .= ' -threshold '.escapeshellarg(round(min(max(intval($amount), 0), 255) / 2.55).'%');
								$commandline .= ' -dither';
								$commandline .= ' -monochrome';
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'rcd':
							if ($this->ImageMagickSwitchAvailable(array('colors', 'dither'))) {
								@list($colors, $dither) = explode('|', $parameter);
								$colors = ($colors                ?  (int) $colors : 256);
								$dither  = ((strlen($dither) > 0) ? (bool) $dither : true);
								$commandline .= ' -colors '.escapeshellarg(max($colors, 8)); // ImageMagick will otherwise fail with "cannot quantize to fewer than 8 colors"
								$commandline .= ($dither ? ' -dither' : ' +dither');
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'flip':
							if ($this->ImageMagickSwitchAvailable(array('flip', 'flop'))) {
								if (strpos(strtolower($parameter), 'x') !== false) {
									$commandline .= ' -flop';
								}
								if (strpos(strtolower($parameter), 'y') !== false) {
									$commandline .= ' -flip';
								}
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'edge':
							if ($this->ImageMagickSwitchAvailable('edge')) {
								$parameter = (!empty($parameter) ? $parameter : 2);
								$commandline .= ' -edge '.escapeshellarg(!empty($parameter) ? intval($parameter) : 1);
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'emb':
							if ($this->ImageMagickSwitchAvailable(array('emboss', 'negate'))) {
								$parameter = (!empty($parameter) ? $parameter : 2);
								$commandline .= ' -emboss '.escapeshellarg(intval($parameter));
								if ($parameter < 2) {
									$commandline .= ' -negate'; // ImageMagick negates the image for some reason with '-emboss 1';
								}
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'lvl':
							@list($band, $method, $threshold) = explode('|', $parameter);
							$band      = ($band ? preg_replace('#[^RGBA\\*]#', '', strtoupper($band))       : '*');
							$method    = ((strlen($method) > 0)    ? intval($method)                        :   2);
							$threshold = ((strlen($threshold) > 0) ? min(max(floatval($threshold), 0), 100) : 0.1);

							$band = preg_replace('#[^RGBA\\*]#', '', strtoupper($band));

							if (($method > 1) && !$this->ImageMagickSwitchAvailable(array('channel', 'contrast-stretch'))) {
								// Because ImageMagick processing happens before PHP-GD filters, and because some
								// clipping is involved in the "lvl" filter, if "lvl" happens before "wb" then the
								// "wb" filter will have (almost) no effect. Therefore, if "wb" is enabled then
								// force the "lvl" filter to be processed by GD, not ImageMagick.
								foreach ($this->fltr as $fltr_key => $fltr_value) {
									list($fltr_cmd) = explode('|', $fltr_value);
									if ($fltr_cmd == 'wb') {
										$this->DebugMessage('Setting "lvl" filter method to "0" (from "'.$method.'") because white-balance filter also enabled', __FILE__, __LINE__);
										$method = 0;
									}
								}
							}

							switch ($method) {
								case 0: // internal RGB
								case 1: // internal grayscale
									break;
								case 2: // ImageMagick "contrast-stretch"
									if ($this->ImageMagickSwitchAvailable('contrast-stretch')) {
										if ($band != '*') {
											$commandline .= ' -channel '.escapeshellarg(strtoupper($band));
										}
										$threshold = preg_replace('#[^0-9\\.]#', '', $threshold); // should be unneccesary, but just to be double-sure
										//$commandline .= ' -contrast-stretch '.escapeshellarg($threshold.'%');
										$commandline .= ' -contrast-stretch \''.$threshold.'%\'';
										if ($band != '*') {
											$commandline .= ' +channel';
										}
										$successfullyProcessedFilters[] = $filterkey;
									}
									break;
								case 3: // ImageMagick "normalize"
									if ($this->ImageMagickSwitchAvailable('normalize')) {
										if ($band != '*') {
											$commandline .= ' -channel '.escapeshellarg(strtoupper($band));
										}
										$commandline .= ' -normalize';
										if ($band != '*') {
											$commandline .= ' +channel';
										}
										$successfullyProcessedFilters[] = $filterkey;
									}
									break;
								default:
									$this->DebugMessage('unsupported method ('.$method.') for "lvl" filter', __FILE__, __LINE__);
									break;
							}
							if (isset($this->fltr[$filterkey]) && ($method > 1)) {
								$this->fltr[$filterkey] = $command.'|'.$band.'|0|'.$threshold;
								$this->DebugMessage('filter "lvl" remapped from method "'.$method.'" to method "0" because ImageMagick support is missing', __FILE__, __LINE__);
							}
							break;

						case 'wb':
							if ($this->ImageMagickSwitchAvailable(array('channel', 'contrast-stretch'))) {
								@list($threshold) = explode('|', $parameter);
								$threshold = (!empty($threshold) ? min(max(floatval($threshold), 0), 100) : 0.1);
								$threshold = preg_replace('#[^0-9\\.]#', '', $threshold); // should be unneccesary, but just to be double-sure
								//$commandline .= ' -channel R -contrast-stretch '.escapeshellarg($threshold.'%'); // doesn't work on Windows because most versions of PHP do not properly
								//$commandline .= ' -channel G -contrast-stretch '.escapeshellarg($threshold.'%'); // escape special characters (such as %) and just replace them with spaces
								//$commandline .= ' -channel B -contrast-stretch '.escapeshellarg($threshold.'%'); // https://bugs.php.net/bug.php?id=43261
								$commandline .= ' -channel R -contrast-stretch \''.$threshold.'%\'';
								$commandline .= ' -channel G -contrast-stretch \''.$threshold.'%\'';
								$commandline .= ' -channel B -contrast-stretch \''.$threshold.'%\'';
								$commandline .= ' +channel';
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'blur':
							if ($this->ImageMagickSwitchAvailable('blur')) {
								@list($radius) = explode('|', $parameter);
								$radius = (!empty($radius) ? min(max(intval($radius), 0), 25) : 1);
								$commandline .= ' -blur '.escapeshellarg($radius);
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'gblr':
							@list($radius) = explode('|', $parameter);
							$radius = (!empty($radius) ? min(max(intval($radius), 0), 25) : 1);
							// "-gaussian" changed to "-gaussian-blur" sometime around 2009
							if ($this->ImageMagickSwitchAvailable('gaussian-blur')) {
								$commandline .= ' -gaussian-blur '.escapeshellarg($radius);
								$successfullyProcessedFilters[] = $filterkey;
							} elseif ($this->ImageMagickSwitchAvailable('gaussian')) {
								$commandline .= ' -gaussian '.escapeshellarg($radius);
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'usm':
							if ($this->ImageMagickSwitchAvailable('unsharp')) {
								@list($amount, $radius, $threshold) = explode('|', $parameter);
								$amount    = ($amount            ? min(max(intval($radius), 0), 255) : 80);
								$radius    = ($radius            ? min(max(intval($radius), 0), 10)  : 0.5);
								$threshold = (strlen($threshold) ? min(max(intval($radius), 0), 50)  : 3);
								$commandline .= ' -unsharp '.escapeshellarg(number_format(($radius * 2) - 1, 2, '.', '').'x1+'.number_format($amount / 100, 2, '.', '').'+'.number_format($threshold / 100, 2, '.', ''));
								$successfullyProcessedFilters[] = $filterkey;
							}
							break;

						case 'bord':
							if ($this->ImageMagickSwitchAvailable(array('border', 'bordercolor', 'thumbnail', 'crop'))) {
								if (!$this->zc) {
									@list($width, $rX, $rY, $color) = explode('|', $parameter);
									$width = intval($width);
									$rX    = intval($rX);
									$rY    = intval($rY);
									if ($width && !$rX && !$rY) {
										if (!phpthumb_functions::IsHexColor($color)) {
											$color = ((!empty($this->bc) && phpthumb_functions::IsHexColor($this->bc)) ? $this->bc : '000000');
										}
										$commandline .= ' -border '.escapeshellarg(intval($width));
										$commandline .= ' -bordercolor '.escapeshellarg('#'.$color);

										if (preg_match('# \\-crop "([0-9]+)x([0-9]+)\\+0\\+0" #', $commandline, $matches)) {
											$commandline = str_replace(' -crop "'.$matches[1].'x'.$matches[2].'+0+0" ', ' -crop '.escapeshellarg(($matches[1] - (2 * $width)).'x'.($matches[2] - (2 * $width)).'+0+0').' ', $commandline);
										} elseif (preg_match('# \\-'.$IMresizeParameter.' "([0-9]+)x([0-9]+)" #', $commandline, $matches)) {
											$commandline = str_replace(' -'.$IMresizeParameter.' "'.$matches[1].'x'.$matches[2].'" ', ' -'.$IMresizeParameter.' '.escapeshellarg(($matches[1] - (2 * $width)).'x'.($matches[2] - (2 * $width))).' ', $commandline);
										}
										$successfullyProcessedFilters[] = $filterkey;
									}
								}
							}
							break;

						case 'crop':
							break;

						case 'sblr':
							break;

						case 'mean':
							break;

						case 'smth':
							break;

						case 'bvl':
							break;

						case 'wmi':
							break;

						case 'wmt':
							break;

						case 'over':
							break;

						case 'hist':
							break;

						case 'fram':
							break;

						case 'drop':
							break;

						case 'mask':
							break;

						case 'elip':
							break;

						case 'ric':
							break;

						case 'stc':
							break;

						case 'size':
							break;

						default:
							$this->DebugMessage('Unknown $this->fltr['.$filterkey.'] ('.$filtercommand.') -- deleting filter command', __FILE__, __LINE__);
							$successfullyProcessedFilters[] = $filterkey;
							break;
					}
					if (!isset($this->fltr[$filterkey])) {
						$this->DebugMessage('Processed $this->fltr['.$filterkey.'] ('.$filtercommand.') with ImageMagick', __FILE__, __LINE__);
					} else {
						$this->DebugMessage('Skipping $this->fltr['.$filterkey.'] ('.$filtercommand.') with ImageMagick', __FILE__, __LINE__);
					}
				}
				$this->DebugMessage('Remaining $this->fltr after ImageMagick: ('.$this->phpThumbDebugVarDump($this->fltr).')', __FILE__, __LINE__);
				if (count($this->fltr) > 0) {
					$this->useRawIMoutput = false;
				}

				if (preg_match('#jpe?g#i', $outputFormat) && $this->q) {
					if ($this->ImageMagickSwitchAvailable(array('quality', 'interlace'))) {
						$commandline .= ' -quality '.escapeshellarg($this->thumbnailQuality);
						if ($this->config_output_interlace) {
							// causes weird things with animated GIF... leave for JPEG only
							$commandline .= ' -interlace line '; // Use Line or Plane to create an interlaced PNG or GIF or progressive JPEG image
						}
					}
				}
				$commandline .= ' '.escapeshellarg(preg_replace('#[/\\\\]#', DIRECTORY_SEPARATOR, $this->sourceFilename).(($outputFormat == 'gif') ? '' : '['.intval($this->sfn).']')); // [0] means first frame of (GIF) animation, can be ignored
				$commandline .= ' '.$outputFormat.':'.escapeshellarg($IMtempfilename);
				if (!$this->iswindows) {
					$commandline .= ' 2>&1';
				}
				$this->DebugMessage('ImageMagick called as ('.$commandline.')', __FILE__, __LINE__);
				$IMresult = phpthumb_functions::SafeExec($commandline);
				clearstatcache();
				if (!@file_exists($IMtempfilename) || !@filesize($IMtempfilename)) {
					$this->FatalError('ImageMagick failed with message ('.trim($IMresult).')');
					$this->DebugMessage('ImageMagick failed with message ('.trim($IMresult).')', __FILE__, __LINE__);
					if ($this->iswindows && !$IMresult) {
						$this->DebugMessage('Check to make sure that PHP has read+write permissions to "'.dirname($IMtempfilename).'"', __FILE__, __LINE__);
					}

				} else {

					foreach ($successfullyProcessedFilters as $dummy => $filterkey) {
						unset($this->fltr[$filterkey]);
					}
					$this->IMresizedData = file_get_contents($IMtempfilename);
					$getimagesize_imresized = @GetImageSize($IMtempfilename);
					$this->DebugMessage('GetImageSize('.$IMtempfilename.') returned [w='.$getimagesize_imresized[0].';h='.$getimagesize_imresized[1].';f='.$getimagesize_imresized[2].']', __FILE__, __LINE__);
					if (($this->config_max_source_pixels > 0) && (($getimagesize_imresized[0] * $getimagesize_imresized[1]) > $this->config_max_source_pixels)) {
						$this->DebugMessage('skipping ImageMagickThumbnailToGD::'.$ImageCreateFunction.'() because IM output is too large ('.$getimagesize_imresized[0].'x'.$getimagesize_imresized[0].' = '.($getimagesize_imresized[0] * $getimagesize_imresized[1]).' > '.$this->config_max_source_pixels.')', __FILE__, __LINE__);
					} elseif (function_exists(@$ImageCreateFunction) && ($this->gdimg_source = @$ImageCreateFunction($IMtempfilename))) {
						$this->source_width  = ImageSX($this->gdimg_source);
						$this->source_height = ImageSY($this->gdimg_source);
						$this->DebugMessage('ImageMagickThumbnailToGD::'.$ImageCreateFunction.'() succeeded, $this->gdimg_source is now ('.$this->source_width.'x'.$this->source_height.')', __FILE__, __LINE__);
						$this->DebugMessage('ImageMagickThumbnailToGD() returning $this->IMresizedData ('.strlen($this->IMresizedData).' bytes)', __FILE__, __LINE__);
					} else {
						$this->useRawIMoutput = true;
						$this->DebugMessage('$this->useRawIMoutput set to TRUE because '.@$ImageCreateFunction.'('.$IMtempfilename.') failed', __FILE__, __LINE__);
					}
					if (file_exists($IMtempfilename)) {
						$this->DebugMessage('deleting "'.$IMtempfilename.'"', __FILE__, __LINE__);
						@unlink($IMtempfilename);
					}
					return true;

				}
				if (file_exists($IMtempfilename)) {
					$this->DebugMessage('deleting "'.$IMtempfilename.'"', __FILE__, __LINE__);
					@unlink($IMtempfilename);
				}

			} elseif ($this->issafemode) {
				$this->DebugMessage('ImageMagickThumbnailToGD() aborting because PHP safe_mode is enabled and phpThumb_tempnam() failed', __FILE__, __LINE__);
				$this->useRawIMoutput = false;
			} else {
				if (file_exists($IMtempfilename)) {
					$this->DebugMessage('deleting "'.$IMtempfilename.'"', __FILE__, __LINE__);
					@unlink($IMtempfilename);
				}
				$this->DebugMessage('ImageMagickThumbnailToGD() aborting, phpThumb_tempnam() failed', __FILE__, __LINE__);
			}
		} else {
			$this->DebugMessage('ImageMagickThumbnailToGD() aborting because ImageMagickCommandlineBase() failed', __FILE__, __LINE__);
		}
		$this->useRawIMoutput = false;
		return false;
	}


	function Rotate() {
		if ($this->ra || $this->ar) {
			if (!function_exists('ImageRotate')) {
				$this->DebugMessage('!function_exists(ImageRotate)', __FILE__, __LINE__);
				return false;
			}
			if (!include_once(dirname(__FILE__).'/phpthumb.filters.php')) {
				$this->DebugMessage('Error including "'.dirname(__FILE__).'/phpthumb.filters.php" which is required for applying filters ('.implode(';', $this->fltr).')', __FILE__, __LINE__);
				return false;
			}

			$this->config_background_hexcolor = ($this->bg ? $this->bg : $this->config_background_hexcolor);
			if (!phpthumb_functions::IsHexColor($this->config_background_hexcolor)) {
				return $this->ErrorImage('Invalid hex color string "'.$this->config_background_hexcolor.'" for parameter "bg"');
			}

			$rotate_angle = 0;
			if ($this->ra) {

				$rotate_angle = floatval($this->ra);

			} else {

				if ($this->ar == 'x') {
					if (phpthumb_functions::version_compare_replacement(phpversion(), '4.2.0', '>=')) {
						if ($this->sourceFilename) {
							if (function_exists('exif_read_data')) {
								if ($exif_data = @exif_read_data($this->sourceFilename, 'IFD0')) {
									// http://sylvana.net/jpegcrop/exif_orientation.html
									switch (@$exif_data['Orientation']) {
										case 1:
											$rotate_angle = 0;
											break;
										case 3:
											$rotate_angle = 180;
											break;
										case 6:
											$rotate_angle = 270;
											break;
										case 8:
											$rotate_angle = 90;
											break;

										default:
											$this->DebugMessage('EXIF auto-rotate failed because unknown $exif_data[Orientation] "'.@$exif_data['Orientation'].'"', __FILE__, __LINE__);
											return false;
											break;
									}
									$this->DebugMessage('EXIF auto-rotate set to '.$rotate_angle.' degrees ($exif_data[Orientation] = "'.@$exif_data['Orientation'].'")', __FILE__, __LINE__);
								} else {
									$this->DebugMessage('failed: exif_read_data('.$this->sourceFilename.')', __FILE__, __LINE__);
									return false;
								}
							} else {
								$this->DebugMessage('!function_exists(exif_read_data)', __FILE__, __LINE__);
								return false;
							}
						} else {
							$this->DebugMessage('Cannot auto-rotate from EXIF data because $this->sourceFilename is empty', __FILE__, __LINE__);
							return false;
						}
					} else {
						$this->DebugMessage('Cannot auto-rotate from EXIF data because PHP is less than v4.2.0 ('.phpversion().')', __FILE__, __LINE__);
						return false;
					}
				} elseif (($this->ar == 'l') && ($this->source_height > $this->source_width)) {
					$rotate_angle = 270;
				} elseif (($this->ar == 'L') && ($this->source_height > $this->source_width)) {
					$rotate_angle = 90;
				} elseif (($this->ar == 'p') && ($this->source_width > $this->source_height)) {
					$rotate_angle = 90;
				} elseif (($this->ar == 'P') && ($this->source_width > $this->source_height)) {
					$rotate_angle = 270;
				}

			}
			if ($rotate_angle % 90) {
				$this->is_alpha = true;
			}
			phpthumb_filters::ImprovedImageRotate($this->gdimg_source, $rotate_angle, $this->config_background_hexcolor, $this->bg);
			$this->source_width  = ImageSX($this->gdimg_source);
			$this->source_height = ImageSY($this->gdimg_source);
		}
		return true;
	}


	function FixedAspectRatio() {
		// optional fixed-dimension images (regardless of aspect ratio)

		if (!$this->far) {
			// do nothing
			return true;
		}

		if (!$this->w || !$this->h) {
			return false;
		}
		$this->thumbnail_width  = $this->w;
		$this->thumbnail_height = $this->h;
		$this->is_alpha = true;
		if ($this->thumbnail_image_width >= $this->thumbnail_width) {

			$aspectratio = $this->thumbnail_image_height / $this->thumbnail_image_width;
			if ($this->w) {
				$this->thumbnail_image_height = round($this->thumbnail_image_width * $aspectratio);
				$this->thumbnail_height = ($this->h ? $this->h : $this->thumbnail_image_height);
			} elseif ($this->thumbnail_image_height < $this->thumbnail_height) {
				$this->thumbnail_image_height = $this->thumbnail_height;
				$this->thumbnail_image_width  = round($this->thumbnail_image_height / $aspectratio);
			}

		} else {

			$aspectratio = $this->thumbnail_image_width / $this->thumbnail_image_height;
			if ($this->h) {
				$this->thumbnail_image_width = round($this->thumbnail_image_height * $aspectratio);
			} elseif ($this->thumbnail_image_width < $this->thumbnail_width) {
				$this->thumbnail_image_width = $this->thumbnail_width;
				$this->thumbnail_image_height  = round($this->thumbnail_image_width / $aspectratio);
			}

		}
		return true;
	}


	function OffsiteDomainIsAllowed($hostname, $allowed_domains) {
		static $domain_is_allowed = array();
		$hostname = strtolower($hostname);
		if (!isset($domain_is_allowed[$hostname])) {
			$domain_is_allowed[$hostname] = false;
			foreach ($allowed_domains as $valid_domain) {
				$starpos = strpos($valid_domain, '*');
				if ($starpos !== false) {
					$valid_domain = substr($valid_domain, $starpos + 1);
					if (preg_match('#'.preg_quote($valid_domain).'$#', $hostname)) {
						$domain_is_allowed[$hostname] = true;
						break;
					}
				} else {
					if (strtolower($valid_domain) === $hostname) {
						$domain_is_allowed[$hostname] = true;
						break;
					}
				}
			}
		}
		return $domain_is_allowed[$hostname];
	}


	function AntiOffsiteLinking() {
		// Optional anti-offsite hijacking of the thumbnail script
		$allow = true;
		if ($allow && $this->config_nooffsitelink_enabled && (@$_SERVER['HTTP_REFERER'] || $this->config_nooffsitelink_require_refer)) {
			$this->DebugMessage('AntiOffsiteLinking() checking $_SERVER[HTTP_REFERER] "'.@$_SERVER['HTTP_REFERER'].'"', __FILE__, __LINE__);
			foreach ($this->config_nooffsitelink_valid_domains as $key => $valid_domain) {
				// $_SERVER['HTTP_HOST'] contains the port number, so strip it out here to make default configuration work
				list($clean_domain) = explode(':', $valid_domain);
				$this->config_nooffsitelink_valid_domains[$key] = $clean_domain;
			}
			$parsed_url = phpthumb_functions::ParseURLbetter(@$_SERVER['HTTP_REFERER']);
			if (!$this->OffsiteDomainIsAllowed(@$parsed_url['host'], $this->config_nooffsitelink_valid_domains)) {
				$allow = false;
				$erase   = $this->config_nooffsitelink_erase_image;
				$message = $this->config_nooffsitelink_text_message;
				//$this->DebugMessage('AntiOffsiteLinking() - "'.@$parsed_url['host'].'" is NOT in $this->config_nooffsitelink_valid_domains ('.implode(';', $this->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);
				$this->ErrorImage('AntiOffsiteLinking() - "'.@$parsed_url['host'].'" is NOT in $this->config_nooffsitelink_valid_domains ('.implode(';', $this->config_nooffsitelink_valid_domains).')');
			} else {
				$this->DebugMessage('AntiOffsiteLinking() - "'.@$parsed_url['host'].'" is in $this->config_nooffsitelink_valid_domains ('.implode(';', $this->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);
			}
		}

		if ($allow && $this->config_nohotlink_enabled && preg_match('#^(f|ht)tps?\://#i', $this->src)) {
			$parsed_url = phpthumb_functions::ParseURLbetter($this->src);
			//if (!phpthumb_functions::CaseInsensitiveInArray(@$parsed_url['host'], $this->config_nohotlink_valid_domains)) {
			if (!$this->OffsiteDomainIsAllowed(@$parsed_url['host'], $this->config_nohotlink_valid_domains)) {
				// This domain is not allowed
				$allow = false;
				$erase   = $this->config_nohotlink_erase_image;
				$message = $this->config_nohotlink_text_message;
				$this->DebugMessage('AntiOffsiteLinking() - "'.$parsed_url['host'].'" is NOT in $this->config_nohotlink_valid_domains ('.implode(';', $this->config_nohotlink_valid_domains).')', __FILE__, __LINE__);
			} else {
				$this->DebugMessage('AntiOffsiteLinking() - "'.$parsed_url['host'].'" is in $this->config_nohotlink_valid_domains ('.implode(';', $this->config_nohotlink_valid_domains).')', __FILE__, __LINE__);
			}
		}

		if ($allow) {
			$this->DebugMessage('AntiOffsiteLinking() says this is allowed', __FILE__, __LINE__);
			return true;
		}

		if (!phpthumb_functions::IsHexColor($this->config_error_bgcolor)) {
			return $this->ErrorImage('Invalid hex color string "'.$this->config_error_bgcolor.'" for $this->config_error_bgcolor');
		}
		if (!phpthumb_functions::IsHexColor($this->config_error_textcolor)) {
			return $this->ErrorImage('Invalid hex color string "'.$this->config_error_textcolor.'" for $this->config_error_textcolor');
		}
		if ($erase) {

			return $this->ErrorImage($message, $this->thumbnail_width, $this->thumbnail_height, $this->config_error_bgcolor, $this->config_error_textcolor, $this->config_error_fontsize);

		} else {

			$this->config_nooffsitelink_watermark_src = $this->ResolveFilenameToAbsolute($this->config_nooffsitelink_watermark_src);
			if (is_file($this->config_nooffsitelink_watermark_src)) {

				if (!include_once(dirname(__FILE__).'/phpthumb.filters.php')) {
					$this->DebugMessage('Error including "'.dirname(__FILE__).'/phpthumb.filters.php" which is required for applying watermark', __FILE__, __LINE__);
					return false;
				}
				$watermark_img = $this->ImageCreateFromStringReplacement(file_get_contents($this->config_nooffsitelink_watermark_src));
				$phpthumbFilters = new phpthumb_filters();
				$phpthumbFilters->phpThumbObject = &$this;
				$opacity = 50;
				$margin  = 5;
				$phpthumbFilters->WatermarkOverlay($this->gdimg_output, $watermark_img, '*', $opacity, $margin);
				ImageDestroy($watermark_img);
				unset($phpthumbFilters);

			} else {

				$nohotlink_text_array = explode("\n", wordwrap($message, floor($this->thumbnail_width / ImageFontWidth($this->config_error_fontsize)), "\n"));
				$nohotlink_text_color = phpthumb_functions::ImageHexColorAllocate($this->gdimg_output, $this->config_error_textcolor);

				$topoffset = round(($this->thumbnail_height - (count($nohotlink_text_array) * ImageFontHeight($this->config_error_fontsize))) / 2);

				$rowcounter = 0;
				$this->DebugMessage('AntiOffsiteLinking() writing '.count($nohotlink_text_array).' lines of text "'.$message.'" (in #'.$this->config_error_textcolor.') on top of image', __FILE__, __LINE__);
				foreach ($nohotlink_text_array as $textline) {
					$leftoffset = max(0, round(($this->thumbnail_width - (strlen($textline) * ImageFontWidth($this->config_error_fontsize))) / 2));
					ImageString($this->gdimg_output, $this->config_error_fontsize, $leftoffset, $topoffset + ($rowcounter++ * ImageFontHeight($this->config_error_fontsize)), $textline, $nohotlink_text_color);
				}

			}

		}
		return true;
	}


	function AlphaChannelFlatten() {
		if (!$this->is_alpha) {
			// image doesn't have alpha transparency, no need to flatten
			$this->DebugMessage('skipping AlphaChannelFlatten() because !$this->is_alpha', __FILE__, __LINE__);
			return false;
		}
		switch ($this->thumbnailFormat) {
			case 'png':
			case 'ico':
				// image has alpha transparency, but output as PNG or ICO which can handle it
				$this->DebugMessage('skipping AlphaChannelFlatten() because ($this->thumbnailFormat == "'.$this->thumbnailFormat.'")', __FILE__, __LINE__);
				return false;
				break;

			case 'gif':
				// image has alpha transparency, but output as GIF which can handle only single-color transparency
				$CurrentImageColorTransparent = ImageColorTransparent($this->gdimg_output);
				if ($CurrentImageColorTransparent == -1) {
					// no transparent color defined

					if (phpthumb_functions::gd_version() < 2.0) {
						$this->DebugMessage('AlphaChannelFlatten() failed because GD version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
						return false;
					}

					if ($img_alpha_mixdown_dither = @ImageCreateTrueColor(ImageSX($this->gdimg_output), ImageSY($this->gdimg_output))) {

						for ($i = 0; $i <= 255; $i++) {
							$dither_color[$i] = ImageColorAllocate($img_alpha_mixdown_dither, $i, $i, $i);
						}

						// scan through current truecolor image copy alpha channel to temp image as grayscale
						for ($x = 0; $x < $this->thumbnail_width; $x++) {
							for ($y = 0; $y < $this->thumbnail_height; $y++) {
								$PixelColor = phpthumb_functions::GetPixelColor($this->gdimg_output, $x, $y);
								ImageSetPixel($img_alpha_mixdown_dither, $x, $y, $dither_color[($PixelColor['alpha'] * 2)]);
							}
						}

						// dither alpha channel grayscale version down to 2 colors
						ImageTrueColorToPalette($img_alpha_mixdown_dither, true, 2);

						// reduce color palette to 256-1 colors (leave one palette position for transparent color)
						ImageTrueColorToPalette($this->gdimg_output, true, 255);

						// allocate a new color for transparent color index
						$TransparentColor = ImageColorAllocate($this->gdimg_output, 1, 254, 253);
						ImageColorTransparent($this->gdimg_output, $TransparentColor);

						// scan through alpha channel image and note pixels with >50% transparency
						$TransparentPixels = array();
						for ($x = 0; $x < $this->thumbnail_width; $x++) {
							for ($y = 0; $y < $this->thumbnail_height; $y++) {
								$AlphaChannelPixel = phpthumb_functions::GetPixelColor($img_alpha_mixdown_dither, $x, $y);
								if ($AlphaChannelPixel['red'] > 127) {
									ImageSetPixel($this->gdimg_output, $x, $y, $TransparentColor);
								}
							}
						}
						ImageDestroy($img_alpha_mixdown_dither);

						$this->DebugMessage('AlphaChannelFlatten() set image to 255+1 colors with transparency for GIF output', __FILE__, __LINE__);
						return true;

					} else {
						$this->DebugMessage('AlphaChannelFlatten() failed ImageCreate('.ImageSX($this->gdimg_output).', '.ImageSY($this->gdimg_output).')', __FILE__, __LINE__);
						return false;
					}

				} else {
					// a single transparent color already defined, leave as-is
					$this->DebugMessage('skipping AlphaChannelFlatten() because ($this->thumbnailFormat == "'.$this->thumbnailFormat.'") and ImageColorTransparent returned "'.$CurrentImageColorTransparent.'"', __FILE__, __LINE__);
					return true;
				}
				break;
		}
		$this->DebugMessage('continuing AlphaChannelFlatten() for output format "'.$this->thumbnailFormat.'"', __FILE__, __LINE__);
		// image has alpha transparency, and is being output in a format that doesn't support it -- flatten
		if ($gdimg_flatten_temp = phpthumb_functions::ImageCreateFunction($this->thumbnail_width, $this->thumbnail_height)) {

			$this->config_background_hexcolor = ($this->bg ? $this->bg : $this->config_background_hexcolor);
			if (!phpthumb_functions::IsHexColor($this->config_background_hexcolor)) {
				return $this->ErrorImage('Invalid hex color string "'.$this->config_background_hexcolor.'" for parameter "bg"');
			}
			$background_color = phpthumb_functions::ImageHexColorAllocate($this->gdimg_output, $this->config_background_hexcolor);
			ImageFilledRectangle($gdimg_flatten_temp, 0, 0, $this->thumbnail_width, $this->thumbnail_height, $background_color);
			ImageCopy($gdimg_flatten_temp, $this->gdimg_output, 0, 0, 0, 0, $this->thumbnail_width, $this->thumbnail_height);

			ImageAlphaBlending($this->gdimg_output, true);
			ImageSaveAlpha($this->gdimg_output, false);
			ImageColorTransparent($this->gdimg_output, -1);
			ImageCopy($this->gdimg_output, $gdimg_flatten_temp, 0, 0, 0, 0, $this->thumbnail_width, $this->thumbnail_height);

			ImageDestroy($gdimg_flatten_temp);
			return true;

		} else {
			$this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
		}
		return false;
	}


	function ApplyFilters() {
		if ($this->fltr && is_array($this->fltr)) {
			if (!include_once(dirname(__FILE__).'/phpthumb.filters.php')) {
				$this->DebugMessage('Error including "'.dirname(__FILE__).'/phpthumb.filters.php" which is required for applying filters ('.implode(';', $this->fltr).')', __FILE__, __LINE__);
				return false;
			}
			$phpthumbFilters = new phpthumb_filters();
			$phpthumbFilters->phpThumbObject = &$this;
			foreach ($this->fltr as $filtercommand) {
				@list($command, $parameter) = explode('|', $filtercommand, 2);
				$this->DebugMessage('Attempting to process filter command "'.$command.'('.$parameter.')"', __FILE__, __LINE__);
				switch ($command) {
					case 'brit': // Brightness
						$phpthumbFilters->Brightness($this->gdimg_output, $parameter);
						break;

					case 'cont': // Contrast
						$phpthumbFilters->Contrast($this->gdimg_output, $parameter);
						break;

					case 'ds': // Desaturation
						$phpthumbFilters->Desaturate($this->gdimg_output, $parameter, '');
						break;

					case 'sat': // Saturation
						$phpthumbFilters->Saturation($this->gdimg_output, $parameter, '');
						break;

					case 'gray': // Grayscale
						$phpthumbFilters->Grayscale($this->gdimg_output);
						break;

					case 'clr': // Colorize
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping Colorize() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							break;
						}
						@list($amount, $color) = explode('|', $parameter, 2);
						$phpthumbFilters->Colorize($this->gdimg_output, $amount, $color);
						break;

					case 'sep': // Sepia
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping Sepia() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							break;
						}
						@list($amount, $color) = explode('|', $parameter, 2);
						$phpthumbFilters->Sepia($this->gdimg_output, $amount, $color);
						break;

					case 'gam': // Gamma correction
						$phpthumbFilters->Gamma($this->gdimg_output, $parameter);
						break;

					case 'neg': // Negative colors
						$phpthumbFilters->Negative($this->gdimg_output);
						break;

					case 'th': // Threshold
						$phpthumbFilters->Threshold($this->gdimg_output, $parameter);
						break;

					case 'rcd': // ReduceColorDepth
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping ReduceColorDepth() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							break;
						}
						@list($colors, $dither) = explode('|', $parameter, 2);
						$colors = ($colors                ?  (int) $colors : 256);
						$dither  = ((strlen($dither) > 0) ? (bool) $dither : true);
						$phpthumbFilters->ReduceColorDepth($this->gdimg_output, $colors, $dither);
						break;

					case 'flip': // Flip
						$phpthumbFilters->Flip($this->gdimg_output, (strpos(strtolower($parameter), 'x') !== false), (strpos(strtolower($parameter), 'y') !== false));
						break;

					case 'edge': // EdgeDetect
						$phpthumbFilters->EdgeDetect($this->gdimg_output);
						break;

					case 'emb': // Emboss
						$phpthumbFilters->Emboss($this->gdimg_output);
						break;

					case 'bvl': // Bevel
						@list($width, $color1, $color2) = explode('|', $parameter, 3);
						$phpthumbFilters->Bevel($this->gdimg_output, $width, $color1, $color2);
						break;

					case 'lvl': // autoLevels
						@list($band, $method, $threshold) = explode('|', $parameter, 3);
						$band      = ($band ? preg_replace('#[^RGBA\\*]#', '', strtoupper($band)) : '*');
						$method    = ((strlen($method) > 0)    ? intval($method)                  :   2);
						$threshold = ((strlen($threshold) > 0) ? floatval($threshold)             : 0.1);

						$phpthumbFilters->HistogramStretch($this->gdimg_output, $band, $method, $threshold);
						break;

					case 'wb': // WhiteBalance
						$phpthumbFilters->WhiteBalance($this->gdimg_output, $parameter);
						break;

					case 'hist': // Histogram overlay
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping HistogramOverlay() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							break;
						}
						@list($bands, $colors, $width, $height, $alignment, $opacity, $margin_x, $margin_y) = explode('|', $parameter, 8);
						$bands     = ($bands     ? $bands     :  '*');
						$colors    = ($colors    ? $colors    :   '');
						$width     = ($width     ? $width     : 0.25);
						$height    = ($height    ? $height    : 0.25);
						$alignment = ($alignment ? $alignment : 'BR');
						$opacity   = ($opacity   ? $opacity   :   50);
						$margin_x  = ($margin_x  ? $margin_x  :    5);
						$margin_y  = $margin_y; // just to note it wasn't forgotten, but let the value always pass unchanged
						$phpthumbFilters->HistogramOverlay($this->gdimg_output, $bands, $colors, $width, $height, $alignment, $opacity, $margin_x, $margin_y);
						break;

					case 'fram': // Frame
						@list($frame_width, $edge_width, $color_frame, $color1, $color2) = explode('|', $parameter, 5);
						$phpthumbFilters->Frame($this->gdimg_output, $frame_width, $edge_width, $color_frame, $color1, $color2);
						break;

					case 'drop': // DropShadow
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping DropShadow() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						$this->is_alpha = true;
						@list($distance, $width, $color, $angle, $fade) = explode('|', $parameter, 5);
						$phpthumbFilters->DropShadow($this->gdimg_output, $distance, $width, $color, $angle, $fade);
						break;

					case 'mask': // Mask cropping
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping Mask() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						@list($mask_filename, $invert) = explode('|', $parameter, 2);
						$mask_filename = $this->ResolveFilenameToAbsolute($mask_filename);
						if (@is_readable($mask_filename) && ($fp_mask = @fopen($mask_filename, 'rb'))) {
							$MaskImageData = '';
							do {
								$buffer = fread($fp_mask, 8192);
								$MaskImageData .= $buffer;
							} while (strlen($buffer) > 0);
							fclose($fp_mask);
							if ($gdimg_mask = $this->ImageCreateFromStringReplacement($MaskImageData)) {
								if ($invert && phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
									ImageFilter($gdimg_mask, IMG_FILTER_NEGATE);
								}
								$this->is_alpha = true;
								$phpthumbFilters->ApplyMask($gdimg_mask, $this->gdimg_output);
								ImageDestroy($gdimg_mask);
							} else {
								$this->DebugMessage('ImageCreateFromStringReplacement() failed for "'.$mask_filename.'"', __FILE__, __LINE__);
							}
						} else {
							$this->DebugMessage('Cannot open mask file "'.$mask_filename.'"', __FILE__, __LINE__);
						}
						break;

					case 'elip': // Elipse cropping
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping Elipse() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						$this->is_alpha = true;
						$phpthumbFilters->Elipse($this->gdimg_output);
						break;

					case 'ric': // RoundedImageCorners
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping RoundedImageCorners() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						@list($radius_x, $radius_y) = explode('|', $parameter, 2);
						if (($radius_x < 1) || ($radius_y < 1)) {
							$this->DebugMessage('Skipping RoundedImageCorners('.$radius_x.', '.$radius_y.') because x/y radius is less than 1', __FILE__, __LINE__);
							break;
						}
						$this->is_alpha = true;
						$phpthumbFilters->RoundedImageCorners($this->gdimg_output, $radius_x, $radius_y);
						break;

					case 'crop': // Crop
						@list($left, $right, $top, $bottom) = explode('|', $parameter, 4);
						$phpthumbFilters->Crop($this->gdimg_output, $left, $right, $top, $bottom);
						break;

					case 'bord': // Border
						@list($border_width, $radius_x, $radius_y, $hexcolor_border) = explode('|', $parameter, 4);
						$this->is_alpha = true;
						$phpthumbFilters->ImageBorder($this->gdimg_output, $border_width, $radius_x, $radius_y, $hexcolor_border);
						break;

					case 'over': // Overlay
						@list($filename, $underlay, $margin, $opacity) = explode('|', $parameter, 4);
						$underlay = (bool) ($underlay              ? $underlay : false);
						$margin   =        ((strlen($margin)  > 0) ? $margin   : ($underlay ? 0.1 : 0.0));
						$opacity  =        ((strlen($opacity) > 0) ? $opacity  : 100);
						if (($margin > 0) && ($margin < 1)) {
							$margin = min(0.499, $margin);
						} elseif (($margin > -1) && ($margin < 0)) {
							$margin = max(-0.499, $margin);
						}

						$filename = $this->ResolveFilenameToAbsolute($filename);
						if (@is_readable($filename) && ($fp_watermark = @fopen($filename, 'rb'))) {
							$WatermarkImageData = '';
							do {
								$buffer = fread($fp_watermark, 8192);
								$WatermarkImageData .= $buffer;
							} while (strlen($buffer) > 0);
							fclose($fp_watermark);
							if ($img_watermark = $this->ImageCreateFromStringReplacement($WatermarkImageData)) {
								if ($margin < 1) {
									$resized_x = max(1, ImageSX($this->gdimg_output) - round(2 * (ImageSX($this->gdimg_output) * $margin)));
									$resized_y = max(1, ImageSY($this->gdimg_output) - round(2 * (ImageSY($this->gdimg_output) * $margin)));
								} else {
									$resized_x = max(1, ImageSX($this->gdimg_output) - round(2 * $margin));
									$resized_y = max(1, ImageSY($this->gdimg_output) - round(2 * $margin));
								}

								if ($underlay) {

									if ($img_watermark_resized = phpthumb_functions::ImageCreateFunction(ImageSX($this->gdimg_output), ImageSY($this->gdimg_output))) {
										ImageAlphaBlending($img_watermark_resized, false);
										ImageSaveAlpha($img_watermark_resized, true);
										$this->ImageResizeFunction($img_watermark_resized, $img_watermark, 0, 0, 0, 0, ImageSX($img_watermark_resized), ImageSY($img_watermark_resized), ImageSX($img_watermark), ImageSY($img_watermark));
										if ($img_source_resized = phpthumb_functions::ImageCreateFunction($resized_x, $resized_y)) {
											ImageAlphaBlending($img_source_resized, false);
											ImageSaveAlpha($img_source_resized, true);
											$this->ImageResizeFunction($img_source_resized, $this->gdimg_output, 0, 0, 0, 0, ImageSX($img_source_resized), ImageSY($img_source_resized), ImageSX($this->gdimg_output), ImageSY($this->gdimg_output));
											$phpthumbFilters->WatermarkOverlay($img_watermark_resized, $img_source_resized, 'C', $opacity, $margin);
											ImageCopy($this->gdimg_output, $img_watermark_resized, 0, 0, 0, 0, ImageSX($this->gdimg_output), ImageSY($this->gdimg_output));
										} else {
											$this->DebugMessage('phpthumb_functions::ImageCreateFunction('.$resized_x.', '.$resized_y.')', __FILE__, __LINE__);
										}
										ImageDestroy($img_watermark_resized);
									} else {
										$this->DebugMessage('phpthumb_functions::ImageCreateFunction('.ImageSX($this->gdimg_output).', '.ImageSY($this->gdimg_output).')', __FILE__, __LINE__);
									}

								} else { // overlay

									if ($img_watermark_resized = phpthumb_functions::ImageCreateFunction($resized_x, $resized_y)) {
										ImageAlphaBlending($img_watermark_resized, false);
										ImageSaveAlpha($img_watermark_resized, true);
										$this->ImageResizeFunction($img_watermark_resized, $img_watermark, 0, 0, 0, 0, ImageSX($img_watermark_resized), ImageSY($img_watermark_resized), ImageSX($img_watermark), ImageSY($img_watermark));
										$phpthumbFilters->WatermarkOverlay($this->gdimg_output, $img_watermark_resized, 'C', $opacity, $margin);
										ImageDestroy($img_watermark_resized);
									} else {
										$this->DebugMessage('phpthumb_functions::ImageCreateFunction('.$resized_x.', '.$resized_y.')', __FILE__, __LINE__);
									}

								}
								ImageDestroy($img_watermark);

							} else {
								$this->DebugMessage('ImageCreateFromStringReplacement() failed for "'.$filename.'"', __FILE__, __LINE__);
							}
						} else {
							$this->DebugMessage('Cannot open overlay file "'.$filename.'"', __FILE__, __LINE__);
						}
						break;

					case 'wmi': // WaterMarkImage
						@list($filename, $alignment, $opacity, $margin['x'], $margin['y'], $rotate_angle) = explode('|', $parameter, 6);
						// $margin can be pixel margin or percent margin if $alignment is text, or max width/height if $alignment is position like "50x75"
						$alignment    = ($alignment            ? $alignment            : 'BR');
						$opacity      = (strlen($opacity)      ? intval($opacity)      : 50);
						$rotate_angle = (strlen($rotate_angle) ? intval($rotate_angle) : 0);
						if (!preg_match('#^([0-9\\.\\-]*)x([0-9\\.\\-]*)$#i', $alignment, $matches)) {
							$margins = array('x', 'y');
							foreach ($margins as $xy) {
								$margin[$xy] = (strlen($margin[$xy]) ? $margin[$xy] : 5);
								if (($margin[$xy] > 0) && ($margin[$xy] < 1)) {
									$margin[$xy] = min(0.499, $margin[$xy]);
								} elseif (($margin[$xy] > -1) && ($margin[$xy] < 0)) {
									$margin[$xy] = max(-0.499, $margin[$xy]);
								}
							}
						}

						$filename = $this->ResolveFilenameToAbsolute($filename);
						if (@is_readable($filename)) {
							if ($img_watermark = $this->ImageCreateFromFilename($filename)) {
								if ($rotate_angle !== 0) {
									$phpthumbFilters->ImprovedImageRotate($img_watermark, $rotate_angle);
								}
								if (preg_match('#^([0-9\\.\\-]*)x([0-9\\.\\-]*)$#i', $alignment, $matches)) {
									$watermark_max_width  = intval($margin['x'] ? $margin['x'] : ImageSX($img_watermark));
									$watermark_max_height = intval($margin['y'] ? $margin['y'] : ImageSY($img_watermark));
									$scale = phpthumb_functions::ScaleToFitInBox(ImageSX($img_watermark), ImageSY($img_watermark), $watermark_max_width, $watermark_max_height, true, true);
									$this->DebugMessage('Scaling watermark by a factor of '.number_format($scale, 4), __FILE__, __LINE__);
									if (($scale > 1) || ($scale < 1)) {
										if ($img_watermark2 = phpthumb_functions::ImageCreateFunction($scale * ImageSX($img_watermark), $scale * ImageSY($img_watermark))) {
											ImageAlphaBlending($img_watermark2, false);
											ImageSaveAlpha($img_watermark2, true);
											$this->ImageResizeFunction($img_watermark2, $img_watermark, 0, 0, 0, 0, ImageSX($img_watermark2), ImageSY($img_watermark2), ImageSX($img_watermark), ImageSY($img_watermark));
											$img_watermark = $img_watermark2;
										} else {
											$this->DebugMessage('ImageCreateFunction('.($scale * ImageSX($img_watermark)).', '.($scale * ImageSX($img_watermark)).') failed', __FILE__, __LINE__);
										}
									}
									$watermark_dest_x = round($matches[1] - (ImageSX($img_watermark) / 2));
									$watermark_dest_y = round($matches[2] - (ImageSY($img_watermark) / 2));
									$alignment = $watermark_dest_x.'x'.$watermark_dest_y;
								}
								$phpthumbFilters->WatermarkOverlay($this->gdimg_output, $img_watermark, $alignment, $opacity, $margin['x'], $margin['y']);
								ImageDestroy($img_watermark);
								if (isset($img_watermark2) && is_resource($img_watermark2)) {
									ImageDestroy($img_watermark2);
								}
							} else {
								$this->DebugMessage('ImageCreateFromFilename() failed for "'.$filename.'"', __FILE__, __LINE__);
							}
						} else {
							$this->DebugMessage('!is_readable('.$filename.')', __FILE__, __LINE__);
						}
						break;

					case 'wmt': // WaterMarkText
						@list($text, $size, $alignment, $hex_color, $ttffont, $opacity, $margin, $angle, $bg_color, $bg_opacity, $fillextend) = explode('|', $parameter, 11);
						$text       = ($text            ? $text       : '');
						$size       = ($size            ? $size       : 3);
						$alignment  = ($alignment       ? $alignment  : 'BR');
						$hex_color  = ($hex_color       ? $hex_color  : '000000');
						$ttffont    = ($ttffont         ? $ttffont    : '');
						$opacity    = (strlen($opacity) ? $opacity    : 50);
						$margin     = (strlen($margin)  ? $margin     : 5);
						$angle      = (strlen($angle)   ? $angle      : 0);
						$bg_color   = ($bg_color        ? $bg_color   : false);
						$bg_opacity = ($bg_opacity      ? $bg_opacity : 0);
						$fillextend = ($fillextend      ? $fillextend : '');

						if (basename($ttffont) == $ttffont) {
							$ttffont = realpath($this->config_ttf_directory.DIRECTORY_SEPARATOR.$ttffont);
						} else {
							$ttffont = $this->ResolveFilenameToAbsolute($ttffont);
						}
						$phpthumbFilters->WatermarkText($this->gdimg_output, $text, $size, $alignment, $hex_color, $ttffont, $opacity, $margin, $angle, $bg_color, $bg_opacity, $fillextend);
						break;

					case 'blur': // Blur
						@list($radius) = explode('|', $parameter, 1);
						$radius = ($radius ? $radius : 1);
						if (phpthumb_functions::gd_version() >= 2) {
							$phpthumbFilters->Blur($this->gdimg_output, $radius);
						} else {
							$this->DebugMessage('Skipping Blur() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
						}
						break;

					case 'gblr': // Gaussian Blur
						$phpthumbFilters->BlurGaussian($this->gdimg_output);
						break;

					case 'sblr': // Selective Blur
						$phpthumbFilters->BlurSelective($this->gdimg_output);
						break;

					case 'mean': // MeanRemoval blur
						$phpthumbFilters->MeanRemoval($this->gdimg_output);
						break;

					case 'smth': // Smooth blur
						$phpthumbFilters->Smooth($this->gdimg_output, $parameter);
						break;

					case 'usm': // UnSharpMask sharpening
						@list($amount, $radius, $threshold) = explode('|', $parameter, 3);
						$amount    = ($amount            ? $amount    : 80);
						$radius    = ($radius            ? $radius    : 0.5);
						$threshold = (strlen($threshold) ? $threshold : 3);
						if (phpthumb_functions::gd_version() >= 2.0) {
							ob_start();
							if (!@include_once(dirname(__FILE__).'/phpthumb.unsharp.php')) {
								$include_error = ob_get_contents();
								if ($include_error) {
									$this->DebugMessage('include_once("'.dirname(__FILE__).'/phpthumb.unsharp.php") generated message: "'.$include_error.'"', __FILE__, __LINE__);
								}
								$this->DebugMessage('Error including "'.dirname(__FILE__).'/phpthumb.unsharp.php" which is required for unsharp masking', __FILE__, __LINE__);
								ob_end_clean();
								return false;
							}
							ob_end_clean();
							phpUnsharpMask::applyUnsharpMask($this->gdimg_output, $amount, $radius, $threshold);
						} else {
							$this->DebugMessage('Skipping unsharp mask because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						break;

					case 'size': // Resize
						@list($newwidth, $newheight, $stretch) = explode('|', $parameter);
						$newwidth  = (!$newwidth  ? ImageSX($this->gdimg_output) : ((($newwidth  > 0) && ($newwidth  < 1)) ? round($newwidth  * ImageSX($this->gdimg_output)) : round($newwidth)));
						$newheight = (!$newheight ? ImageSY($this->gdimg_output) : ((($newheight > 0) && ($newheight < 1)) ? round($newheight * ImageSY($this->gdimg_output)) : round($newheight)));
						$stretch   = ($stretch ? true : false);
						if ($stretch) {
							$scale_x = phpthumb_functions::ScaleToFitInBox(ImageSX($this->gdimg_output), ImageSX($this->gdimg_output), $newwidth,  $newwidth,  true, true);
							$scale_y = phpthumb_functions::ScaleToFitInBox(ImageSY($this->gdimg_output), ImageSY($this->gdimg_output), $newheight, $newheight, true, true);
						} else {
							$scale_x = phpthumb_functions::ScaleToFitInBox(ImageSX($this->gdimg_output), ImageSY($this->gdimg_output), $newwidth, $newheight, true, true);
							$scale_y = $scale_x;
						}
						$this->DebugMessage('Scaling watermark ('.($stretch ? 'with' : 'without').' stretch) by a factor of "'.number_format($scale_x, 4).' x '.number_format($scale_y, 4).'"', __FILE__, __LINE__);
						if (($scale_x > 1) || ($scale_x < 1) || ($scale_y > 1) || ($scale_y < 1)) {
							if ($img_temp = phpthumb_functions::ImageCreateFunction(ImageSX($this->gdimg_output), ImageSY($this->gdimg_output))) {
								ImageCopy($img_temp, $this->gdimg_output, 0, 0, 0, 0, ImageSX($this->gdimg_output), ImageSY($this->gdimg_output));
								//ImageDestroy($this->gdimg_output);
								if ($this->gdimg_output = phpthumb_functions::ImageCreateFunction($scale_x * ImageSX($img_temp), $scale_y * ImageSY($img_temp))) {
									ImageAlphaBlending($this->gdimg_output, false);
									ImageSaveAlpha($this->gdimg_output, true);
									$this->ImageResizeFunction($this->gdimg_output, $img_temp, 0, 0, 0, 0, ImageSX($this->gdimg_output), ImageSY($this->gdimg_output), ImageSX($img_temp), ImageSY($img_temp));
								} else {
									$this->DebugMessage('ImageCreateFunction('.($scale_x * ImageSX($img_temp)).', '.($scale_y * ImageSY($img_temp)).') failed', __FILE__, __LINE__);
								}
								ImageDestroy($img_temp);
							} else {
								$this->DebugMessage('ImageCreateFunction('.ImageSX($this->gdimg_output).', '.ImageSY($this->gdimg_output).') failed', __FILE__, __LINE__);
							}
						}
						break;

					case 'rot': // ROTate
						@list($angle, $bgcolor) = explode('|', $parameter, 2);
						$phpthumbFilters->ImprovedImageRotate($this->gdimg_output, $angle, $bgcolor);
						break;

					case 'stc': // Source Transparent Color
						@list($hexcolor, $min_limit, $max_limit) = explode('|', $parameter, 3);
						if (!phpthumb_functions::IsHexColor($hexcolor)) {
							$this->DebugMessage('Skipping SourceTransparentColor hex color is invalid ('.$hexcolor.')', __FILE__, __LINE__);
							return false;
						}
						$min_limit = (strlen($min_limit) ? $min_limit :  5);
						$max_limit = (strlen($max_limit) ? $max_limit : 10);
						if ($gdimg_mask = $phpthumbFilters->SourceTransparentColorMask($this->gdimg_output, $hexcolor, $min_limit, $max_limit)) {
							$this->is_alpha = true;
							$phpthumbFilters->ApplyMask($gdimg_mask, $this->gdimg_output);
							ImageDestroy($gdimg_mask);
						} else {
							$this->DebugMessage('SourceTransparentColorMask() failed for "'.$mask_filename.'"', __FILE__, __LINE__);
						}
						break;
				}
				$this->DebugMessage('Finished processing filter command "'.$command.'('.$parameter.')"', __FILE__, __LINE__);
			}
		}
		return true;
	}


	function MaxFileSize() {
		if (phpthumb_functions::gd_version() < 2) {
			$this->DebugMessage('Skipping MaxFileSize() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
			return false;
		}
		if ($this->maxb > 0) {
			switch ($this->thumbnailFormat) {
				case 'png':
				case 'gif':
					$imgRenderFunction = 'image'.$this->thumbnailFormat;

					ob_start();
					$imgRenderFunction($this->gdimg_output);
					$imgdata = ob_get_contents();
					ob_end_clean();

					if (strlen($imgdata) > $this->maxb) {
						for ($i = 8; $i >= 1; $i--) {
							$tempIMG = ImageCreateTrueColor(ImageSX($this->gdimg_output), ImageSY($this->gdimg_output));
							ImageCopy($tempIMG, $this->gdimg_output, 0, 0, 0, 0, ImageSX($this->gdimg_output), ImageSY($this->gdimg_output));
							ImageTrueColorToPalette($tempIMG, true, pow(2, $i));
							ob_start();
							$imgRenderFunction($tempIMG);
							$imgdata = ob_get_contents();
							ob_end_clean();

							if (strlen($imgdata) <= $this->maxb) {
								ImageTrueColorToPalette($this->gdimg_output, true, pow(2, $i));
								break;
							}
						}
					}
					if (strlen($imgdata) > $this->maxb) {
						ImageTrueColorToPalette($this->gdimg_output, true, pow(2, $i));
						return false;
					}
					break;

				case 'jpeg':
					ob_start();
					ImageJPEG($this->gdimg_output);
					$imgdata = ob_get_contents();
					ob_end_clean();

					$OriginalJPEGquality = $this->thumbnailQuality;
					if (strlen($imgdata) > $this->maxb) {
						for ($i = 3; $i < 20; $i++) {
							$q = round(100 * (1 - log10($i / 2)));
							ob_start();
							ImageJPEG($this->gdimg_output, null, $q);
							$imgdata = ob_get_contents();
							ob_end_clean();

							$this->thumbnailQuality = $q;
							if (strlen($imgdata) <= $this->maxb) {
								break;
							}
						}
					}
					if (strlen($imgdata) > $this->maxb) {
						return false;
					}
					break;

				default:
					return false;
					break;
			}
		}
		return true;
	}


	function CalculateThumbnailDimensions() {
		$this->DebugMessage('CalculateThumbnailDimensions() starting with [W,H,sx,sy,sw,sh] initially set to ['.$this->source_width.','.$this->source_height.','.$this->sx.','.$this->sy.','.$this->sw.','.$this->sh.']', __FILE__, __LINE__);
//echo $this->source_width.'x'.$this->source_height.'<hr>';
		$this->thumbnailCropX = ($this->sx ? (($this->sx >= 2) ? $this->sx : round($this->sx * $this->source_width))  : 0);
//echo $this->thumbnailCropX.'<br>';
		$this->thumbnailCropY = ($this->sy ? (($this->sy >= 2) ? $this->sy : round($this->sy * $this->source_height)) : 0);
//echo $this->thumbnailCropY.'<br>';
		$this->thumbnailCropW = ($this->sw ? (($this->sw >= 2) ? $this->sw : round($this->sw * $this->source_width))  : $this->source_width);
//echo $this->thumbnailCropW.'<br>';
		$this->thumbnailCropH = ($this->sh ? (($this->sh >= 2) ? $this->sh : round($this->sh * $this->source_height)) : $this->source_height);
//echo $this->thumbnailCropH.'<hr>';

		// limit source area to original image area
		$this->thumbnailCropW = max(1, min($this->thumbnailCropW, $this->source_width  - $this->thumbnailCropX));
		$this->thumbnailCropH = max(1, min($this->thumbnailCropH, $this->source_height - $this->thumbnailCropY));

		$this->DebugMessage('CalculateThumbnailDimensions() starting with [x,y,w,h] initially set to ['.$this->thumbnailCropX.','.$this->thumbnailCropY.','.$this->thumbnailCropW.','.$this->thumbnailCropH.']', __FILE__, __LINE__);


		if ($this->zc && $this->w && $this->h) {
			// Zoom Crop
			// retain proportional resizing we did above, but crop off larger dimension so smaller
			// dimension fully fits available space

			$scaling_X = $this->source_width  / $this->w;
			$scaling_Y = $this->source_height / $this->h;
			if ($scaling_X > $scaling_Y) {
				// some of the width will need to be cropped
				$allowable_width = $this->source_width / $scaling_X * $scaling_Y;
				$this->thumbnailCropW = round($allowable_width);
				$this->thumbnailCropX = round(($this->source_width - $allowable_width) / 2);

			} elseif ($scaling_Y > $scaling_X) {
				// some of the height will need to be cropped
				$allowable_height = $this->source_height / $scaling_Y * $scaling_X;
				$this->thumbnailCropH = round($allowable_height);
				$this->thumbnailCropY = round(($this->source_height - $allowable_height) / 2);

			} else {
				// image fits perfectly, no cropping needed
			}
			$this->thumbnail_width  = $this->w;
			$this->thumbnail_height = $this->h;
			$this->thumbnail_image_width  = $this->thumbnail_width;
			$this->thumbnail_image_height = $this->thumbnail_height;

		} elseif ($this->iar && $this->w && $this->h) {

			// Ignore Aspect Ratio
			// stretch image to fit exactly 'w' x 'h'
			$this->thumbnail_width  = $this->w;
			$this->thumbnail_height = $this->h;
			$this->thumbnail_image_width  = $this->thumbnail_width;
			$this->thumbnail_image_height = $this->thumbnail_height;

		} else {

			$original_aspect_ratio = $this->thumbnailCropW / $this->thumbnailCropH;
			if ($this->aoe) {
				if ($this->w && $this->h) {
					$maxwidth  = min($this->w, $this->h * $original_aspect_ratio);
					$maxheight = min($this->h, $this->w / $original_aspect_ratio);
				} elseif ($this->w) {
					$maxwidth  = $this->w;
					$maxheight = $this->w / $original_aspect_ratio;
				} elseif ($this->h) {
					$maxwidth  = $this->h * $original_aspect_ratio;
					$maxheight = $this->h;
				} else {
					$maxwidth  = $this->thumbnailCropW;
					$maxheight = $this->thumbnailCropH;
				}
			} else {
				$maxwidth  = phpthumb_functions::nonempty_min($this->w, $this->thumbnailCropW, $this->config_output_maxwidth);
				$maxheight = phpthumb_functions::nonempty_min($this->h, $this->thumbnailCropH, $this->config_output_maxheight);
//echo $maxwidth.'x'.$maxheight.'<br>';
				$maxwidth  = min($maxwidth, $maxheight * $original_aspect_ratio);
				$maxheight = min($maxheight, $maxwidth / $original_aspect_ratio);
//echo $maxwidth.'x'.$maxheight.'<hr>';
			}

			$this->thumbnail_image_width  = $maxwidth;
			$this->thumbnail_image_height = $maxheight;
			$this->thumbnail_width  = $maxwidth;
			$this->thumbnail_height = $maxheight;

			$this->FixedAspectRatio();
		}

		$this->thumbnail_width  = max(1, floor($this->thumbnail_width));
		$this->thumbnail_height = max(1, floor($this->thumbnail_height));
		return true;
	}


	function CreateGDoutput() {
		$this->CalculateThumbnailDimensions();

		// Create the GD image (either true-color or 256-color, depending on GD version)
		$this->gdimg_output = phpthumb_functions::ImageCreateFunction($this->thumbnail_width, $this->thumbnail_height);

		// Images that have transparency must have the background filled with the configured 'bg' color
		// otherwise the transparent color will appear as black
		ImageSaveAlpha($this->gdimg_output, true);
		if ($this->is_alpha && phpthumb_functions::gd_version() >= 2) {

			ImageAlphaBlending($this->gdimg_output, false);
			$output_full_alpha = phpthumb_functions::ImageColorAllocateAlphaSafe($this->gdimg_output, 255, 255, 255, 127);
			ImageFilledRectangle($this->gdimg_output, 0, 0, $this->thumbnail_width, $this->thumbnail_height, $output_full_alpha);

		} else {

			$current_transparent_color = ImageColorTransparent($this->gdimg_source);
			if ($this->bg || (@$current_transparent_color >= 0)) {

				$this->config_background_hexcolor = ($this->bg ? $this->bg : $this->config_background_hexcolor);
				if (!phpthumb_functions::IsHexColor($this->config_background_hexcolor)) {
					return $this->ErrorImage('Invalid hex color string "'.$this->config_background_hexcolor.'" for parameter "bg"');
				}
				$background_color = phpthumb_functions::ImageHexColorAllocate($this->gdimg_output, $this->config_background_hexcolor);
				ImageFilledRectangle($this->gdimg_output, 0, 0, $this->thumbnail_width, $this->thumbnail_height, $background_color);

			}

		}
		$this->DebugMessage('CreateGDoutput() returning canvas "'.$this->thumbnail_width.'x'.$this->thumbnail_height.'"', __FILE__, __LINE__);
		return true;
	}

	function SetOrientationDependantWidthHeight() {
		$this->DebugMessage('SetOrientationDependantWidthHeight() starting with "'.$this->source_width.'"x"'.$this->source_height.'"', __FILE__, __LINE__);
		if ($this->source_height > $this->source_width) {
			// portrait
			$this->w = phpthumb_functions::OneOfThese($this->wp, $this->w, $this->ws, $this->wl);
			$this->h = phpthumb_functions::OneOfThese($this->hp, $this->h, $this->hs, $this->hl);
		} elseif ($this->source_height < $this->source_width) {
			// landscape
			$this->w = phpthumb_functions::OneOfThese($this->wl, $this->w, $this->ws, $this->wp);
			$this->h = phpthumb_functions::OneOfThese($this->hl, $this->h, $this->hs, $this->hp);
		} else {
			// square
			$this->w = phpthumb_functions::OneOfThese($this->ws, $this->w, $this->wl, $this->wp);
			$this->h = phpthumb_functions::OneOfThese($this->hs, $this->h, $this->hl, $this->hp);
		}
		//$this->w = round($this->w ? $this->w : (($this->h && $this->source_height) ? $this->h * $this->source_width  / $this->source_height : $this->w));
		//$this->h = round($this->h ? $this->h : (($this->w && $this->source_width)  ? $this->w * $this->source_height / $this->source_width  : $this->h));
		$this->DebugMessage('SetOrientationDependantWidthHeight() setting w="'.intval($this->w).'", h="'.intval($this->h).'"', __FILE__, __LINE__);
		return true;
	}

	function ExtractEXIFgetImageSize() {
		$this->DebugMessage('starting ExtractEXIFgetImageSize()', __FILE__, __LINE__);

		if (preg_match('#^http:#i', $this->src) && !$this->sourceFilename && $this->rawImageData) {
			!$this->SourceDataToTempFile();
		}
		if (is_null($this->getimagesizeinfo)) {
			if ($this->sourceFilename) {
				$this->getimagesizeinfo = @GetImageSize($this->sourceFilename);
				$this->source_width  = $this->getimagesizeinfo[0];
				$this->source_height = $this->getimagesizeinfo[1];
				$this->DebugMessage('GetImageSize('.$this->sourceFilename.') says image is '.$this->source_width.'x'.$this->source_height, __FILE__, __LINE__);
			} else {
				$this->DebugMessage('skipping GetImageSize() because $this->sourceFilename is empty', __FILE__, __LINE__);
			}
		} else {
			$this->DebugMessage('skipping GetImageSize() because !is_null($this->getimagesizeinfo)', __FILE__, __LINE__);
		}

		if (is_resource($this->gdimg_source)) {

			$this->source_width  = ImageSX($this->gdimg_source);
			$this->source_height = ImageSY($this->gdimg_source);

			$this->SetOrientationDependantWidthHeight();

		} elseif ($this->rawImageData && !$this->sourceFilename) {

			if ($this->SourceImageIsTooLarge($this->source_width, $this->source_height)) {
				$this->DebugMessage('NOT bypassing EXIF and GetImageSize sections because source image is too large for GD ('.$this->source_width.'x'.$this->source_width.'='.($this->source_width * $this->source_height * 5).'MB)', __FILE__, __LINE__);
			} else {
				$this->DebugMessage('bypassing EXIF and GetImageSize sections because $this->rawImageData is set, and $this->sourceFilename is not set, and source image is not too large for GD ('.$this->source_width.'x'.$this->source_width.'='.($this->source_width * $this->source_height * 5).'MB)', __FILE__, __LINE__);
			}

		}

		if (is_null($this->getimagesizeinfo)) {
			$this->getimagesizeinfo = @GetImageSize($this->sourceFilename);
		}

		if (!empty($this->getimagesizeinfo)) {
			// great
			$this->getimagesizeinfo['filesize'] = @filesize($this->sourceFilename);
		} elseif (!$this->rawImageData) {
			$this->DebugMessage('GetImageSize("'.$this->sourceFilename.'") failed', __FILE__, __LINE__);
		}

		if ($this->config_prefer_imagemagick) {
			if ($this->ImageMagickThumbnailToGD()) {
				return true;
			}
			$this->DebugMessage('ImageMagickThumbnailToGD() failed', __FILE__, __LINE__);
		}

		$this->source_width  = $this->getimagesizeinfo[0];
		$this->source_height = $this->getimagesizeinfo[1];

		$this->SetOrientationDependantWidthHeight();

		if (phpthumb_functions::version_compare_replacement(phpversion(), '4.2.0', '>=') && function_exists('exif_read_data')) {
			switch ($this->getimagesizeinfo[2]) {
				case IMAGETYPE_JPEG:
				case IMAGETYPE_TIFF_II:
				case IMAGETYPE_TIFF_MM:
					$this->exif_raw_data = @exif_read_data($this->sourceFilename, 0, true);
					break;
			}
		}
		if (function_exists('exif_thumbnail') && ($this->getimagesizeinfo[2] == IMAGETYPE_JPEG)) {
			// Extract EXIF info from JPEGs

			$this->exif_thumbnail_width  = '';
			$this->exif_thumbnail_height = '';
			$this->exif_thumbnail_type   = '';

			// The parameters width, height and imagetype are available since PHP v4.3.0
			if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.0', '>=')) {

				$this->exif_thumbnail_data = @exif_thumbnail($this->sourceFilename, $this->exif_thumbnail_width, $this->exif_thumbnail_height, $this->exif_thumbnail_type);

			} else {

				// older versions of exif_thumbnail output an error message but NOT return false on failure
				ob_start();
				$this->exif_thumbnail_data = exif_thumbnail($this->sourceFilename);
				$exit_thumbnail_error = ob_get_contents();
				ob_end_clean();
				if (!$exit_thumbnail_error && $this->exif_thumbnail_data) {

					if ($gdimg_exif_temp = $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data, false)) {
						$this->exif_thumbnail_width  = ImageSX($gdimg_exif_temp);
						$this->exif_thumbnail_height = ImageSY($gdimg_exif_temp);
						$this->exif_thumbnail_type   = 2; // (2 == JPEG) before PHP v4.3.0 only JPEG format EXIF thumbnails are returned
						unset($gdimg_exif_temp);
					} else {
						return $this->ErrorImage('Failed - $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data) in '.__FILE__.' on line '.__LINE__);
					}

				}

			}

		} elseif (!function_exists('exif_thumbnail')) {

			$this->DebugMessage('exif_thumbnail() does not exist, cannot extract EXIF thumbnail', __FILE__, __LINE__);

		}

		$this->DebugMessage('EXIF thumbnail extraction: (size='.strlen($this->exif_thumbnail_data).'; type="'.$this->exif_thumbnail_type.'"; '.intval($this->exif_thumbnail_width).'x'.intval($this->exif_thumbnail_height).')', __FILE__, __LINE__);

		// see if EXIF thumbnail can be used directly with no processing
		if ($this->config_use_exif_thumbnail_for_speed && $this->exif_thumbnail_data) {
			while (true) {
				if (!$this->xto) {
					$source_ar = $this->source_width / $this->source_height;
					$exif_ar = $this->exif_thumbnail_width / $this->exif_thumbnail_height;
					if (number_format($source_ar, 2) != number_format($exif_ar, 2)) {
						$this->DebugMessage('not using EXIF thumbnail because $source_ar != $exif_ar ('.$source_ar.' != '.$exif_ar.')', __FILE__, __LINE__);
						break;
					}
					if ($this->w && ($this->w != $this->exif_thumbnail_width)) {
						$this->DebugMessage('not using EXIF thumbnail because $this->w != $this->exif_thumbnail_width ('.$this->w.' != '.$this->exif_thumbnail_width.')', __FILE__, __LINE__);
						break;
					}
					if ($this->h && ($this->h != $this->exif_thumbnail_height)) {
						$this->DebugMessage('not using EXIF thumbnail because $this->h != $this->exif_thumbnail_height ('.$this->h.' != '.$this->exif_thumbnail_height.')', __FILE__, __LINE__);
						break;
					}
					$CannotBeSetParameters = array('sx', 'sy', 'sh', 'sw', 'far', 'bg', 'bc', 'fltr', 'phpThumbDebug');
					foreach ($CannotBeSetParameters as $parameter) {
						if ($this->$parameter) {
							break 2;
						}
					}
				}

				$this->DebugMessage('setting $this->gdimg_source = $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data)', __FILE__, __LINE__);
				$this->gdimg_source = $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data);
				$this->source_width  = ImageSX($this->gdimg_source);
				$this->source_height = ImageSY($this->gdimg_source);
				return true;
			}
		}

		if (($this->config_max_source_pixels > 0) && (($this->source_width * $this->source_height) > $this->config_max_source_pixels)) {

			// Source image is larger than would fit in available PHP memory.
			// If ImageMagick is installed, use it to generate the thumbnail.
			// Else, if an EXIF thumbnail is available, use that as the source image.
			// Otherwise, no choice but to fail with an error message
			$this->DebugMessage('image is '.$this->source_width.'x'.$this->source_height.' and therefore contains more pixels ('.($this->source_width * $this->source_height).') than $this->config_max_source_pixels setting ('.$this->config_max_source_pixels.')', __FILE__, __LINE__);
			if (!$this->config_prefer_imagemagick && $this->ImageMagickThumbnailToGD()) {
				// excellent, we have a thumbnailed source image
				return true;
			}

		}
		return true;
	}


	function SetCacheFilename() {
		if (!is_null($this->cache_filename)) {
			$this->DebugMessage('$this->cache_filename already set, skipping SetCacheFilename()', __FILE__, __LINE__);
			return true;
		}
		if (is_null($this->config_cache_directory)) {
			$this->setCacheDirectory();
			if (!$this->config_cache_directory) {
				$this->DebugMessage('SetCacheFilename() failed because $this->config_cache_directory is empty', __FILE__, __LINE__);
				return false;
			}
		}
		$this->setOutputFormat();

		if (!$this->sourceFilename && !$this->rawImageData && $this->src) {
			$this->sourceFilename = $this->ResolveFilenameToAbsolute($this->src);
		}

		if ($this->config_cache_default_only_suffix && $this->sourceFilename) {
			// simplified cache filenames:
			// only use default parameters in phpThumb.config.php
			// substitute source filename into * in $this->config_cache_default_only_suffix
			// (eg: '*_thumb' becomes 'picture_thumb.jpg')
			if (strpos($this->config_cache_default_only_suffix, '*') === false) {
				$this->DebugMessage('aborting simplified caching filename because no * in "'.$this->config_cache_default_only_suffix.'"', __FILE__, __LINE__);
			} else {
				preg_match('#(.+)(\\.[a-z0-9]+)?$#i', basename($this->sourceFilename), $matches);
				$this->cache_filename = $this->config_cache_directory.DIRECTORY_SEPARATOR.rawurlencode(str_replace('*', @$matches[1], $this->config_cache_default_only_suffix)).'.'.strtolower($this->thumbnailFormat);
				return true;
			}
		}

		$this->cache_filename = '';
		$broad_directory_name = '';
		if ($this->new) {
			$broad_directory_name = strtolower(md5($this->new));
			$this->cache_filename .= '_new'.$broad_directory_name;
		} elseif ($this->md5s) {
			// source image MD5 hash provided
			$this->DebugMessage('SetCacheFilename() _raw set from $this->md5s = "'.$this->md5s.'"', __FILE__, __LINE__);
			$broad_directory_name = $this->md5s;
			$this->cache_filename .= '_raw'.$this->md5s;
		} elseif (!$this->src && $this->rawImageData) {
			$this->DebugMessage('SetCacheFilename() _raw set from md5($this->rawImageData) = "'.md5($this->rawImageData).'"', __FILE__, __LINE__);
			$broad_directory_name = strtolower(md5($this->rawImageData));
			$this->cache_filename .= '_raw'.$broad_directory_name;
		} else {
			$this->DebugMessage('SetCacheFilename() _src set from md5($this->sourceFilename) "'.$this->sourceFilename.'" = "'.md5($this->sourceFilename).'"', __FILE__, __LINE__);
			$broad_directory_name = strtolower(md5($this->sourceFilename));
			$this->cache_filename .= '_src'.$broad_directory_name;
		}
		if (!empty($_SERVER['HTTP_REFERER']) && $this->config_nooffsitelink_enabled) {
			$parsed_url1 = @phpthumb_functions::ParseURLbetter(@$_SERVER['HTTP_REFERER']);
			$parsed_url2 = @phpthumb_functions::ParseURLbetter('http://'.@$_SERVER['HTTP_HOST']);
			if (@$parsed_url1['host'] && @$parsed_url2['host'] && ($parsed_url1['host'] != $parsed_url2['host'])) {
				// include "_offsite" only if nooffsitelink_enabled and if referrer doesn't match the domain of the current server
				$this->cache_filename .= '_offsite';
			}
		}

		$ParametersString = '';
		if ($this->fltr && is_array($this->fltr)) {
			$ParametersString .= '_fltr'.implode('_fltr', $this->fltr);
		}
		$FilenameParameters1 = array('ar', 'bg', 'bc', 'far', 'sx', 'sy', 'sw', 'sh', 'zc');
		foreach ($FilenameParameters1 as $key) {
			if ($this->$key) {
				$ParametersString .= '_'.$key.$this->$key;
			}
		}
		$FilenameParameters2 = array('h', 'w', 'wl', 'wp', 'ws', 'hp', 'hs', 'xto', 'ra', 'iar', 'aoe', 'maxb', 'sfn', 'dpi');
		foreach ($FilenameParameters2 as $key) {
			if ($this->$key) {
				$ParametersString .= '_'.$key.intval($this->$key);
			}
		}
		if ($this->thumbnailFormat == 'jpeg') {
			// only JPEG output has variable quality option
			$ParametersString .= '_q'.intval($this->thumbnailQuality);
		}
		$this->DebugMessage('SetCacheFilename() _par set from md5('.$ParametersString.')', __FILE__, __LINE__);
		$this->cache_filename .= '_par'.strtolower(md5($ParametersString));

		if ($this->md5s) {
			// source image MD5 hash provided
			// do not source image modification date --
			// cached image will be used even if file was modified or removed
		} elseif (!$this->config_cache_source_filemtime_ignore_remote && preg_match('#^(f|ht)tps?\://#i', $this->src)) {
			$this->cache_filename .= '_dat'.intval(phpthumb_functions::filedate_remote($this->src));
		} elseif (!$this->config_cache_source_filemtime_ignore_local && $this->src && !$this->rawImageData) {
			$this->cache_filename .= '_dat'.intval(@filemtime($this->sourceFilename));
		}

		$this->cache_filename .= '.'.strtolower($this->thumbnailFormat);
		$broad_directories = '';
		for ($i = 0; $i < $this->config_cache_directory_depth; $i++) {
			$broad_directories .= DIRECTORY_SEPARATOR.substr($broad_directory_name, 0, $i + 1);
		}

		$this->cache_filename = $this->config_cache_directory.$broad_directories.DIRECTORY_SEPARATOR.$this->config_cache_prefix.rawurlencode($this->cache_filename);
		return true;
	}


	function SourceImageIsTooLarge($width, $height) {
		if (!$this->config_max_source_pixels) {
			return false;
		}
		if (function_exists('memory_get_usage')) {
			$available_memory = max(intval(ini_get('memory_limit')), intval(get_cfg_var('memory_limit'))) * 1048576;
			$available_memory -= memory_get_usage();
			return (bool) (($width * $height * 5) > $available_memory);
		}
		return (bool) (($width * $height) > $this->config_max_source_pixels);
	}

	function ImageCreateFromFilename($filename) {
		// try to create GD image source directly via GD, if possible,
		// rather than buffering to memory and creating with ImageCreateFromString
		$ImageCreateWasAttempted = false;
		$gd_image = false;

		$this->DebugMessage('starting ImageCreateFromFilename('.$filename.')', __FILE__, __LINE__);
		if ($filename && ($getimagesizeinfo = @GetImageSize($filename))) {
			if (!$this->SourceImageIsTooLarge($getimagesizeinfo[0], $getimagesizeinfo[1])) {
				$ImageCreateFromFunction = array(
					1  => 'ImageCreateFromGIF',
					2  => 'ImageCreateFromJPEG',
					3  => 'ImageCreateFromPNG',
					15 => 'ImageCreateFromWBMP',
				);
				$this->DebugMessage('ImageCreateFromFilename found ($getimagesizeinfo[2]=='.@$getimagesizeinfo[2].')', __FILE__, __LINE__);
				switch (@$getimagesizeinfo[2]) {
					case 1:  // GIF
					case 2:  // JPEG
					case 3:  // PNG
					case 15: // WBMP
						$ImageCreateFromFunctionName = $ImageCreateFromFunction[$getimagesizeinfo[2]];
						if (function_exists($ImageCreateFromFunctionName)) {
							$this->DebugMessage('Calling '.$ImageCreateFromFunctionName.'('.$filename.')', __FILE__, __LINE__);
							$ImageCreateWasAttempted = true;
							$gd_image = $ImageCreateFromFunctionName($filename);
						} else {
							$this->DebugMessage('NOT calling '.$ImageCreateFromFunctionName.'('.$filename.') because !function_exists('.$ImageCreateFromFunctionName.')', __FILE__, __LINE__);
						}
						break;

					case 4:  // SWF
					case 5:  // PSD
					case 6:  // BMP
					case 7:  // TIFF (LE)
					case 8:  // TIFF (BE)
					case 9:  // JPC
					case 10: // JP2
					case 11: // JPX
					case 12: // JB2
					case 13: // SWC
					case 14: // IFF
					case 16: // XBM
						$this->DebugMessage('No built-in image creation function for image type "'.@$getimagesizeinfo[2].'" ($getimagesizeinfo[2])', __FILE__, __LINE__);
						break;

					default:
						$this->DebugMessage('Unknown value for $getimagesizeinfo[2]: "'.@$getimagesizeinfo[2].'"', __FILE__, __LINE__);
						break;
				}
			} else {
				$this->DebugMessage('image is '.$getimagesizeinfo[0].'x'.$getimagesizeinfo[1].' and therefore contains more pixels ('.($getimagesizeinfo[0] * $getimagesizeinfo[1]).') than $this->config_max_source_pixels setting ('.$this->config_max_source_pixels.')', __FILE__, __LINE__);
				return false;
			}
		} else {
			$this->DebugMessage('empty $filename or GetImageSize('.$filename.') failed', __FILE__, __LINE__);
		}

		if (!$gd_image) {
			// cannot create from filename, attempt to create source image with ImageCreateFromString, if possible
			if ($ImageCreateWasAttempted) {
				$this->DebugMessage(@$ImageCreateFromFunctionName.'() was attempted but FAILED', __FILE__, __LINE__);
			}
			$this->DebugMessage('Populating $rawimagedata', __FILE__, __LINE__);
			$rawimagedata = '';
			if ($fp = @fopen($filename, 'rb')) {
				$filesize = filesize($filename);
				$blocksize = 8192;
				$blockreads = ceil($filesize / $blocksize);
				for ($i = 0; $i < $blockreads; $i++) {
					$rawimagedata .= fread($fp, $blocksize);
				}
				fclose($fp);
			} else {
				$this->DebugMessage('cannot fopen('.$filename.')', __FILE__, __LINE__);
			}
			if ($rawimagedata) {
				$this->DebugMessage('attempting ImageCreateFromStringReplacement($rawimagedata ('.strlen($rawimagedata).' bytes), true)', __FILE__, __LINE__);
				$gd_image = $this->ImageCreateFromStringReplacement($rawimagedata, true);
			}
		}
		return $gd_image;
	}

	function SourceImageToGD() {
		if (is_resource($this->gdimg_source)) {
			$this->source_width  = ImageSX($this->gdimg_source);
			$this->source_height = ImageSY($this->gdimg_source);
			$this->DebugMessage('skipping SourceImageToGD() because $this->gdimg_source is already a resource ('.$this->source_width.'x'.$this->source_height.')', __FILE__, __LINE__);
			return true;
		}
		$this->DebugMessage('starting SourceImageToGD()', __FILE__, __LINE__);

		if ($this->config_prefer_imagemagick) {
			if (empty($this->sourceFilename) && !empty($this->rawImageData)) {
				$this->DebugMessage('Copying raw image data to temp file and trying again with ImageMagick', __FILE__, __LINE__);
				if ($tempnam = $this->phpThumb_tempnam()) {
					if (file_put_contents($tempnam, $this->rawImageData)) {
						$this->sourceFilename = $tempnam;
						if ($this->ImageMagickThumbnailToGD()) {
							// excellent, we have a thumbnailed source image
							$this->DebugMessage('ImageMagickThumbnailToGD() succeeded', __FILE__, __LINE__);
						} else {
							$this->DebugMessage('ImageMagickThumbnailToGD() failed', __FILE__, __LINE__);
						}
					} else {
						$this->DebugMessage('failed to put $this->rawImageData into temp file "'.$tempnam.'"', __FILE__, __LINE__);
					}
				} else {
					$this->DebugMessage('failed to generate temp file name', __FILE__, __LINE__);
				}
			}
		}
		if (!$this->gdimg_source && $this->rawImageData) {

			if ($this->SourceImageIsTooLarge($this->source_width, $this->source_height)) {
				$memory_get_usage = (function_exists('memory_get_usage') ? memory_get_usage() : 0);
				return $this->ErrorImage('Source image is too large ('.$this->source_width.'x'.$this->source_height.' = '.number_format($this->source_width * $this->source_height / 1000000, 1).'Mpx, max='.number_format($this->config_max_source_pixels / 1000000, 1).'Mpx) for GD creation (either install ImageMagick or increase PHP memory_limit to at least '.ceil(($memory_get_usage + (5 * $this->source_width * $this->source_height)) / 1048576).'M).');
			}
			if ($this->md5s && ($this->md5s != md5($this->rawImageData))) {
				return $this->ErrorImage('$this->md5s != md5($this->rawImageData)'."\n".'"'.$this->md5s.'" != '."\n".'"'.md5($this->rawImageData).'"');
			}
			//if ($this->issafemode) {
			//	return $this->ErrorImage('Cannot generate thumbnails from raw image data when PHP SAFE_MODE enabled');
			//}
			$this->gdimg_source = $this->ImageCreateFromStringReplacement($this->rawImageData);
			if (!$this->gdimg_source) {
				if (substr($this->rawImageData, 0, 2) === 'BM') {
					$this->getimagesizeinfo[2] = 6; // BMP
				} elseif (substr($this->rawImageData, 0, 4) === 'II'."\x2A\x00") {
					$this->getimagesizeinfo[2] = 7; // TIFF (littlendian)
				} elseif (substr($this->rawImageData, 0, 4) === 'MM'."\x00\x2A") {
					$this->getimagesizeinfo[2] = 8; // TIFF (bigendian)
				}
				$this->DebugMessage('SourceImageToGD.ImageCreateFromStringReplacement() failed with unknown image type "'.substr($this->rawImageData, 0, 4).'" ('.phpthumb_functions::HexCharDisplay(substr($this->rawImageData, 0, 4)).')', __FILE__, __LINE__);
//				return $this->ErrorImage('Unknown image type identified by "'.substr($this->rawImageData, 0, 4).'" ('.phpthumb_functions::HexCharDisplay(substr($this->rawImageData, 0, 4)).') in SourceImageToGD()['.__LINE__.']');
			}

		} elseif (!$this->gdimg_source && $this->sourceFilename) {

			if ($this->md5s && ($this->md5s != phpthumb_functions::md5_file_safe($this->sourceFilename))) {
				return $this->ErrorImage('$this->md5s != md5(sourceFilename)'."\n".'"'.$this->md5s.'" != '."\n".'"'.phpthumb_functions::md5_file_safe($this->sourceFilename).'"');
			}
			switch (@$this->getimagesizeinfo[2]) {
				case 1:
				case 3:
					// GIF or PNG input file may have transparency
					$this->is_alpha = true;
					break;
			}
			if (!$this->SourceImageIsTooLarge($this->source_width, $this->source_height)) {
				$this->gdimg_source = $this->ImageCreateFromFilename($this->sourceFilename);
			}

		}

		while (true) {
			if ($this->gdimg_source) {
				$this->DebugMessage('Not using EXIF thumbnail data because $this->gdimg_source is already set', __FILE__, __LINE__);
				break;
			}
			if (!$this->exif_thumbnail_data) {
				$this->DebugMessage('Not using EXIF thumbnail data because $this->exif_thumbnail_data is empty', __FILE__, __LINE__);
				break;
			}
			if (ini_get('safe_mode')) {
				if (!$this->SourceImageIsTooLarge($this->source_width, $this->source_height)) {
					$this->DebugMessage('Using EXIF thumbnail data because source image too large and safe_mode enabled', __FILE__, __LINE__);
					$this->aoe = true;
				} else {
					break;
				}
			} else {
				if (!$this->config_use_exif_thumbnail_for_speed) {
					$this->DebugMessage('Not using EXIF thumbnail data because $this->config_use_exif_thumbnail_for_speed is FALSE', __FILE__, __LINE__);
					break;
				}
				if (($this->thumbnailCropX != 0) || ($this->thumbnailCropY != 0)) {
					$this->DebugMessage('Not using EXIF thumbnail data because source cropping is enabled ('.$this->thumbnailCropX.','.$this->thumbnailCropY.')', __FILE__, __LINE__);
					break;
				}
				if (($this->w > $this->exif_thumbnail_width) || ($this->h > $this->exif_thumbnail_height)) {
					$this->DebugMessage('Not using EXIF thumbnail data because EXIF thumbnail is too small ('.$this->exif_thumbnail_width.'x'.$this->exif_thumbnail_height.' vs '.$this->w.'x'.$this->h.')', __FILE__, __LINE__);
					break;
				}
				$source_ar = $this->source_width / $this->source_height;
				$exif_ar   = $this->exif_thumbnail_width / $this->exif_thumbnail_height;
				if (number_format($source_ar, 2) != number_format($exif_ar, 2)) {
					$this->DebugMessage('not using EXIF thumbnail because $source_ar != $exif_ar ('.$source_ar.' != '.$exif_ar.')', __FILE__, __LINE__);
					break;
				}
			}

			// EXIF thumbnail exists, and is equal to or larger than destination thumbnail, and will be use as source image
			$this->DebugMessage('Trying to use EXIF thumbnail as source image', __FILE__, __LINE__);

			if ($gdimg_exif_temp = $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data, false)) {

				$this->DebugMessage('Successfully using EXIF thumbnail as source image', __FILE__, __LINE__);
				$this->gdimg_source   = $gdimg_exif_temp;
				$this->source_width   = $this->exif_thumbnail_width;
				$this->source_height  = $this->exif_thumbnail_height;
				$this->thumbnailCropW = $this->source_width;
				$this->thumbnailCropH = $this->source_height;
				return true;

			} else {
				$this->DebugMessage('$this->ImageCreateFromStringReplacement($this->exif_thumbnail_data, false) failed', __FILE__, __LINE__);
			}

			break;
		}

		if (!$this->gdimg_source) {
			$this->DebugMessage('$this->gdimg_source is still empty', __FILE__, __LINE__);

			$this->DebugMessage('ImageMagickThumbnailToGD() failed', __FILE__, __LINE__);

			$imageHeader = '';
			$gd_info = gd_info();
			$GDreadSupport = false;
			switch (@$this->getimagesizeinfo[2]) {
				case 1:
					$imageHeader = 'Content-Type: image/gif';
					$GDreadSupport = (bool) @$gd_info['GIF Read Support'];
					break;
				case 2:
					$imageHeader = 'Content-Type: image/jpeg';
					$GDreadSupport = (bool) @$gd_info['JPG Support'];
					break;
				case 3:
					$imageHeader = 'Content-Type: image/png';
					$GDreadSupport = (bool) @$gd_info['PNG Support'];
					break;
			}
			if ($imageHeader) {
				// cannot create image for whatever reason (maybe ImageCreateFromJPEG et al are not available?)
				// and ImageMagick is not available either, no choice but to output original (not resized/modified) data and exit
				if ($this->config_error_die_on_source_failure) {
					$errormessages = array();
					$errormessages[] = 'All attempts to create GD image source failed.';
					if ($this->fatalerror) {
						$errormessages[] = $this->fatalerror;
					}
					if ($this->issafemode) {
						$errormessages[] = 'Safe Mode enabled, therefore ImageMagick is unavailable. (disable Safe Mode if possible)';
					} elseif (!$this->ImageMagickVersion()) {
						$errormessages[] = 'ImageMagick is not installed (it is highly recommended that you install it).';
					}
					if ($this->SourceImageIsTooLarge($this->getimagesizeinfo[0], $this->getimagesizeinfo[1])) {
						$memory_get_usage = (function_exists('memory_get_usage') ? memory_get_usage() : 0);
						$errormessages[] = 'Source image is too large ('.$this->getimagesizeinfo[0].'x'.$this->getimagesizeinfo[1].' = '.number_format($this->getimagesizeinfo[0] * $this->getimagesizeinfo[1] / 1000000, 1).'Mpx, max='.number_format($this->config_max_source_pixels / 1000000, 1).'Mpx) for GD creation (either install ImageMagick or increase PHP memory_limit to at least '.ceil(($memory_get_usage + (5 * $this->getimagesizeinfo[0] * $this->getimagesizeinfo[1])) / 1048576).'M).';
					} elseif (!$GDreadSupport) {
						$errormessages[] = 'GD does not have read support for "'.$imageHeader.'".';
					} else {
						$errormessages[] = 'Source image probably corrupt.';
					}
					$this->ErrorImage(implode("\n", $errormessages));

				} else {
					$this->DebugMessage('All attempts to create GD image source failed ('.(ini_get('safe_mode') ? 'Safe Mode enabled, ImageMagick unavailable and source image probably too large for GD': ($GDreadSupport ? 'source image probably corrupt' : 'GD does not have read support for "'.$imageHeader.'"')).'), cannot generate thumbnail');
					//$this->DebugMessage('All attempts to create GD image source failed ('.($GDreadSupport ? 'source image probably corrupt' : 'GD does not have read support for "'.$imageHeader.'"').'), outputing raw image', __FILE__, __LINE__);
					//if (!$this->phpThumbDebug) {
					//	header($imageHeader);
					//	echo $this->rawImageData;
					//	exit;
					//}
					return false;
				}
			}

			//switch (substr($this->rawImageData, 0, 2)) {
			//	case 'BM':
			switch (@$this->getimagesizeinfo[2]) {
				case 6:
					ob_start();
					if (!@include_once(dirname(__FILE__).'/phpthumb.bmp.php')) {
						ob_end_clean();
						return $this->ErrorImage('include_once('.dirname(__FILE__).'/phpthumb.bmp.php) failed');
					}
					ob_end_clean();
					if ($fp = @fopen($this->sourceFilename, 'rb')) {
						$this->rawImageData = '';
						while (!feof($fp)) {
							$this->rawImageData .= fread($fp, 32768);
						}
						fclose($fp);
					}
					$phpthumb_bmp = new phpthumb_bmp();
					$this->gdimg_source = $phpthumb_bmp->phpthumb_bmp2gd($this->rawImageData, (phpthumb_functions::gd_version() >= 2.0));
					unset($phpthumb_bmp);
					if ($this->gdimg_source) {
						$this->DebugMessage('$phpthumb_bmp->phpthumb_bmp2gd() succeeded', __FILE__, __LINE__);
					} else {
						return $this->ErrorImage($this->ImageMagickVersion() ? 'ImageMagick failed on BMP source conversion' : 'phpthumb_bmp2gd() failed');
					}
					break;
			//}
			//switch (substr($this->rawImageData, 0, 4)) {
			//	case 'II'."\x2A\x00":
			//	case 'MM'."\x00\x2A":
				case 7:
				case 8:
					return $this->ErrorImage($this->ImageMagickVersion() ? 'ImageMagick failed on TIFF source conversion' : 'ImageMagick is unavailable and phpThumb() does not support TIFF source images without it');
					break;

				//case "\xD7\xCD\xC6\x9A":
				//	return $this->ErrorImage($this->ImageMagickVersion() ? 'ImageMagick failed on WMF source conversion' : 'ImageMagick is unavailable and phpThumb() does not support WMF source images without it');
				//	break;
			}

			if (!$this->gdimg_source) {
				$HeaderFourBytes = '';
				if ($this->rawImageData) {
					$HeaderFourBytes = substr($this->rawImageData, 0, 4);
				} elseif ($this->sourceFilename) {
					if ($fp = @fopen($this->sourceFilename, 'rb')) {
						$HeaderFourBytes = fread($fp, 4);
						fclose($fp);
					} else {
						return $this->ErrorImage('failed to open "'.$this->sourceFilename.'" SourceImageToGD() ['.__LINE__.']');
					}
				} else {
					return $this->ErrorImage('Unable to create image, neither filename nor image data suppplied in SourceImageToGD() ['.__LINE__.']');
				}
				if (!$this->ImageMagickVersion() && !phpthumb_functions::gd_version()) {
					return $this->ErrorImage('Neither GD nor ImageMagick seem to be installed on this server. At least one (preferably GD), or better both, MUST be installed for phpThumb to work.');
				} elseif ($HeaderFourBytes == "\xD7\xCD\xC6\x9A") { // WMF
					return $this->ErrorImage($this->ImageMagickVersion() ? 'ImageMagick failed on WMF source conversion' : 'ImageMagick is unavailable and phpThumb() does not support WMF source images without it');
				} elseif ($HeaderFourBytes == '%PDF') { // "%PDF"
					return $this->ErrorImage($this->ImageMagickVersion() ? 'ImageMagick and GhostScript are both required for PDF source images; GhostScript may not be properly configured' : 'ImageMagick and/or GhostScript are unavailable and phpThumb() does not support PDF source images without them');
				} elseif (substr($HeaderFourBytes, 0, 3) == "\xFF\xD8\xFF") { // JPEG
					return $this->ErrorImage('Image (JPEG) is too large for PHP-GD memory_limit, please install ImageMagick or increase php.ini memory_limit setting');
				} elseif ($HeaderFourBytes == '%PNG') { // "%PNG"
					return $this->ErrorImage('Image (PNG) is too large for PHP-GD memory_limit, please install ImageMagick or increase php.ini memory_limit setting');
				} elseif (substr($HeaderFourBytes, 0, 3) == 'GIF') { // GIF
					return $this->ErrorImage('Image (GIF) is too large for PHP-GD memory_limit, please install ImageMagick or increase php.ini memory_limit setting');
				}
				return $this->ErrorImage('Unknown image type identified by "'.$HeaderFourBytes.'" ('.phpthumb_functions::HexCharDisplay($HeaderFourBytes).') in SourceImageToGD() ['.__LINE__.']');
			}
		}

		if (!$this->gdimg_source) {
			if ($gdimg_exif_temp = $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data, false)) {
				$this->DebugMessage('All other attempts failed, but successfully using EXIF thumbnail as source image', __FILE__, __LINE__);
				$this->gdimg_source = $gdimg_exif_temp;
				// override allow-enlarging setting if EXIF thumbnail is the only source available
				// otherwise thumbnails larger than the EXIF thumbnail will be created at EXIF size
				$this->aoe = true;
				return true;
			}
			return false;
		}

		$this->source_width  = ImageSX($this->gdimg_source);
		$this->source_height = ImageSY($this->gdimg_source);
		return true;
	}


	function phpThumbDebugVarDump($var) {
		if (is_null($var)) {
			return 'NULL';
		} elseif (is_bool($var)) {
			return ($var ? 'TRUE' : 'FALSE');
		} elseif (is_string($var)) {
			return 'string('.strlen($var).')'.str_repeat(' ', max(0, 3 - strlen(strlen($var)))).' "'.$var.'"';
		} elseif (is_int($var)) {
			return 'integer     '.$var;
		} elseif (is_float($var)) {
			return 'float       '.$var;
		} elseif (is_array($var)) {
			ob_start();
			var_dump($var);
			$vardumpoutput = ob_get_contents();
			ob_end_clean();
			return strtr($vardumpoutput, "\n\r\t", '   ');
		}
		return gettype($var);
	}

	function phpThumbDebug($level='') {
		if ($level && ($this->phpThumbDebug !== $level)) {
			return true;
		}
		if ($this->config_disable_debug) {
			return $this->ErrorImage('phpThumbDebug disabled');
		}

		$FunctionsExistance  = array('exif_thumbnail', 'gd_info', 'image_type_to_mime_type', 'GetImageSize', 'ImageCopyResampled', 'ImageCopyResized', 'ImageCreate', 'ImageCreateFromString', 'ImageCreateTrueColor', 'ImageIsTrueColor', 'ImageRotate', 'ImageTypes', 'version_compare', 'ImageCreateFromGIF', 'ImageCreateFromJPEG', 'ImageCreateFromPNG', 'ImageCreateFromWBMP', 'ImageCreateFromXBM', 'ImageCreateFromXPM', 'ImageCreateFromString', 'ImageCreateFromGD', 'ImageCreateFromGD2', 'ImageCreateFromGD2Part', 'ImageJPEG', 'ImageGIF', 'ImagePNG', 'ImageWBMP');
		$ParameterNames      = array('src', 'new', 'w', 'h', 'f', 'q', 'sx', 'sy', 'sw', 'sh', 'far', 'bg', 'bc', 'file', 'goto', 'err', 'xto', 'ra', 'ar', 'aoe', 'iar', 'maxb');
		$ConfigVariableNames = array('document_root', 'temp_directory', 'output_format', 'output_maxwidth', 'output_maxheight', 'error_message_image_default', 'error_bgcolor', 'error_textcolor', 'error_fontsize', 'error_die_on_error', 'error_silent_die_on_error', 'error_die_on_source_failure', 'nohotlink_enabled', 'nohotlink_valid_domains', 'nohotlink_erase_image', 'nohotlink_text_message', 'nooffsitelink_enabled', 'nooffsitelink_valid_domains', 'nooffsitelink_require_refer', 'nooffsitelink_erase_image', 'nooffsitelink_text_message', 'high_security_enabled', 'allow_src_above_docroot', 'allow_src_above_phpthumb', 'max_source_pixels', 'use_exif_thumbnail_for_speed', 'border_hexcolor', 'background_hexcolor', 'ttf_directory', 'disable_pathinfo_parsing', 'disable_imagecopyresampled');
		$OtherVariableNames  = array('phpThumbDebug', 'thumbnailQuality', 'thumbnailFormat', 'gdimg_output', 'gdimg_source', 'sourceFilename', 'source_width', 'source_height', 'thumbnailCropX', 'thumbnailCropY', 'thumbnailCropW', 'thumbnailCropH', 'exif_thumbnail_width', 'exif_thumbnail_height', 'exif_thumbnail_type', 'thumbnail_width', 'thumbnail_height', 'thumbnail_image_width', 'thumbnail_image_height');

		$DebugOutput = array();
		$DebugOutput[] = 'phpThumb() version          = '.$this->phpthumb_version;
		$DebugOutput[] = 'phpversion()                = '.@phpversion();
		$DebugOutput[] = 'PHP_OS                      = '.PHP_OS;
		$DebugOutput[] = '$_SERVER[SERVER_SOFTWARE]   = '.@$_SERVER['SERVER_SOFTWARE'];
		$DebugOutput[] = '__FILE__                    = '.__FILE__;
		$DebugOutput[] = 'realpath(.)                 = '.@realpath('.');
		$DebugOutput[] = '$_SERVER[PHP_SELF]          = '.@$_SERVER['PHP_SELF'];
		$DebugOutput[] = '$_SERVER[HOST_NAME]         = '.@$_SERVER['HOST_NAME'];
		$DebugOutput[] = '$_SERVER[HTTP_REFERER]      = '.@$_SERVER['HTTP_REFERER'];
		$DebugOutput[] = '$_SERVER[QUERY_STRING]      = '.@$_SERVER['QUERY_STRING'];
		$DebugOutput[] = '$_SERVER[PATH_INFO]         = '.@$_SERVER['PATH_INFO'];
		$DebugOutput[] = '$_SERVER[DOCUMENT_ROOT]     = '.@$_SERVER['DOCUMENT_ROOT'];
		$DebugOutput[] = 'getenv(DOCUMENT_ROOT)       = '.@getenv('DOCUMENT_ROOT');
		$DebugOutput[] = '';

		$DebugOutput[] = 'get_magic_quotes_gpc()         = '.$this->phpThumbDebugVarDump(@get_magic_quotes_gpc());
		$DebugOutput[] = 'get_magic_quotes_runtime()     = '.$this->phpThumbDebugVarDump(@get_magic_quotes_runtime());
		$DebugOutput[] = 'error_reporting()              = '.$this->phpThumbDebugVarDump(error_reporting());
		$DebugOutput[] = 'ini_get(error_reporting)       = '.$this->phpThumbDebugVarDump(@ini_get('error_reporting'));
		$DebugOutput[] = 'ini_get(display_errors)        = '.$this->phpThumbDebugVarDump(@ini_get('display_errors'));
		$DebugOutput[] = 'ini_get(allow_url_fopen)       = '.$this->phpThumbDebugVarDump(@ini_get('allow_url_fopen'));
		$DebugOutput[] = 'ini_get(disable_functions)     = '.$this->phpThumbDebugVarDump(@ini_get('disable_functions'));
		$DebugOutput[] = 'get_cfg_var(disable_functions) = '.$this->phpThumbDebugVarDump(@get_cfg_var('disable_functions'));
		$DebugOutput[] = 'ini_get(safe_mode)             = '.$this->phpThumbDebugVarDump(@ini_get('safe_mode'));
		$DebugOutput[] = 'ini_get(open_basedir)          = '.$this->phpThumbDebugVarDump(@ini_get('open_basedir'));
		$DebugOutput[] = 'ini_get(max_execution_time)    = '.$this->phpThumbDebugVarDump(@ini_get('max_execution_time'));
		$DebugOutput[] = 'ini_get(memory_limit)          = '.$this->phpThumbDebugVarDump(@ini_get('memory_limit'));
		$DebugOutput[] = 'get_cfg_var(memory_limit)      = '.$this->phpThumbDebugVarDump(@get_cfg_var('memory_limit'));
		$DebugOutput[] = 'memory_get_usage()             = '.(function_exists('memory_get_usage') ? $this->phpThumbDebugVarDump(@memory_get_usage()) : 'n/a');
		$DebugOutput[] = '';

		$DebugOutput[] = '$this->config_prefer_imagemagick            = '.$this->phpThumbDebugVarDump($this->config_prefer_imagemagick);
		$DebugOutput[] = '$this->config_imagemagick_path              = '.$this->phpThumbDebugVarDump($this->config_imagemagick_path);
		$DebugOutput[] = '$this->ImageMagickWhichConvert()            = '.$this->ImageMagickWhichConvert();
		$IMpathUsed = ($this->config_imagemagick_path ? $this->config_imagemagick_path : $this->ImageMagickWhichConvert());
		$DebugOutput[] = '[actual ImageMagick path used]              = '.$this->phpThumbDebugVarDump($IMpathUsed);
		$DebugOutput[] = 'file_exists([actual ImageMagick path used]) = '.$this->phpThumbDebugVarDump(@file_exists($IMpathUsed));
		$DebugOutput[] = 'ImageMagickVersion(false)                   = '.$this->ImageMagickVersion(false);
		$DebugOutput[] = 'ImageMagickVersion(true)                    = '.$this->ImageMagickVersion(true);
		$DebugOutput[] = '';

		$DebugOutput[] = '$this->config_cache_directory               = '.$this->phpThumbDebugVarDump($this->config_cache_directory);
		$DebugOutput[] = '$this->config_cache_directory_depth         = '.$this->phpThumbDebugVarDump($this->config_cache_directory_depth);
		$DebugOutput[] = '$this->config_cache_disable_warning         = '.$this->phpThumbDebugVarDump($this->config_cache_disable_warning);
		$DebugOutput[] = '$this->config_cache_maxage                  = '.$this->phpThumbDebugVarDump($this->config_cache_maxage);
		$DebugOutput[] = '$this->config_cache_maxsize                 = '.$this->phpThumbDebugVarDump($this->config_cache_maxsize);
		$DebugOutput[] = '$this->config_cache_maxfiles                = '.$this->phpThumbDebugVarDump($this->config_cache_maxfiles);
		$DebugOutput[] = '$this->config_cache_force_passthru          = '.$this->phpThumbDebugVarDump($this->config_cache_force_passthru);
		$DebugOutput[] = '$this->cache_filename                       = '.$this->phpThumbDebugVarDump($this->cache_filename);
		$DebugOutput[] = 'is_readable($this->config_cache_directory)  = '.$this->phpThumbDebugVarDump(@is_readable($this->config_cache_directory));
		$DebugOutput[] = 'is_writable($this->config_cache_directory)  = '.$this->phpThumbDebugVarDump(@is_writable($this->config_cache_directory));
		$DebugOutput[] = 'is_readable($this->cache_filename)          = '.$this->phpThumbDebugVarDump(@is_readable($this->cache_filename));
		$DebugOutput[] = 'is_writable($this->cache_filename)          = '.(@file_exists($this->cache_filename) ? $this->phpThumbDebugVarDump(@is_writable($this->cache_filename)) : 'n/a');
		$DebugOutput[] = '';

		foreach ($ConfigVariableNames as $varname) {
			$varname = 'config_'.$varname;
			$value = $this->$varname;
			$DebugOutput[] = '$this->'.str_pad($varname, 37, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
		}
		$DebugOutput[] = '';
		foreach ($OtherVariableNames as $varname) {
			$value = $this->$varname;
			$DebugOutput[] = '$this->'.str_pad($varname, 27, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
		}
		$DebugOutput[] = 'strlen($this->rawImageData)        = '.strlen(@$this->rawImageData);
		$DebugOutput[] = 'strlen($this->exif_thumbnail_data) = '.strlen(@$this->exif_thumbnail_data);
		$DebugOutput[] = '';

		foreach ($ParameterNames as $varname) {
			$value = $this->$varname;
			$DebugOutput[] = '$this->'.str_pad($varname, 4, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
		}
		$DebugOutput[] = '';

		foreach ($FunctionsExistance as $functionname) {
			$DebugOutput[] = 'builtin_function_exists('.$functionname.')'.str_repeat(' ', 23 - strlen($functionname)).' = '.$this->phpThumbDebugVarDump(phpthumb_functions::builtin_function_exists($functionname));
		}
		$DebugOutput[] = '';

		$gd_info = gd_info();
		foreach ($gd_info as $key => $value) {
			$DebugOutput[] = 'gd_info.'.str_pad($key, 34, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
		}
		$DebugOutput[] = '';

		$exif_info = phpthumb_functions::exif_info();
		foreach ($exif_info as $key => $value) {
			$DebugOutput[] = 'exif_info.'.str_pad($key, 26, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
		}
		$DebugOutput[] = '';

		if ($ApacheLookupURIarray = phpthumb_functions::ApacheLookupURIarray(dirname(@$_SERVER['PHP_SELF']))) {
			foreach ($ApacheLookupURIarray as $key => $value) {
				$DebugOutput[] = 'ApacheLookupURIarray.'.str_pad($key, 15, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
			}
		} else {
				$DebugOutput[] = 'ApacheLookupURIarray() -- FAILED';
		}
		$DebugOutput[] = '';

		if (isset($_GET) && is_array($_GET)) {
			foreach ($_GET as $key => $value) {
				$DebugOutput[] = '$_GET['.$key.']'.str_repeat(' ', 30 - strlen($key)).'= '.$this->phpThumbDebugVarDump($value);
			}
		}
		if (isset($_POST) && is_array($_POST)) {
			foreach ($_POST as $key => $value) {
				$DebugOutput[] = '$_POST['.$key.']'.str_repeat(' ', 29 - strlen($key)).'= '.$this->phpThumbDebugVarDump($value);
			}
		}
		$DebugOutput[] = '';

		$DebugOutput[] = '$this->debugmessages:';
		foreach ($this->debugmessages as $errorstring) {
			$DebugOutput[] = '  * '.$errorstring;
		}
		$DebugOutput[] = '';

		$DebugOutput[] = '$this->debugtiming:';
		foreach ($this->debugtiming as $timestamp => $timingstring) {
			$DebugOutput[] = '  * '.$timestamp.' '.$timingstring;
		}
		$DebugOutput[] = '  * Total processing time: '.number_format(max(array_keys($this->debugtiming)) - min(array_keys($this->debugtiming)), 6);

		$this->f = (isset($_GET['f']) ? $_GET['f'] : $this->f); // debug modes 0-2 don't recognize text mode otherwise
		return $this->ErrorImage(implode("\n", $DebugOutput), 700, 500, true);
	}

	function FatalError($text) {
		if (is_null($this->fatalerror)) {
			$this->fatalerror = $text;
		}
		return true;
	}

	function ErrorImage($text, $width=0, $height=0, $forcedisplay=false) {
		$width  = ($width  ? $width  : $this->config_error_image_width);
		$height = ($height ? $height : $this->config_error_image_height);

		$text = 'phpThumb() v'.$this->phpthumb_version."\n".'http://phpthumb.sourceforge.net'."\n\n".($this->config_disable_debug ? 'Error messages disabled.'."\n\n".'edit phpThumb.config.php and (temporarily) set'."\n".'$PHPTHUMB_CONFIG[\'disable_debug\'] = false;'."\n".'to view the details of this error' : $text);

		$this->FatalError($text);
		$this->DebugMessage($text, __FILE__, __LINE__);
		$this->purgeTempFiles();
		if ($this->config_error_silent_die_on_error) {
			exit;
		}
		if ($this->phpThumbDebug && !$forcedisplay) {
			return false;
		}
		if (!$this->config_error_die_on_error && !$forcedisplay) {
			return false;
		}
		if ($this->err || $this->config_error_message_image_default) {
			// Show generic custom error image instead of error message
			// for use on production sites where you don't want debug messages
			if (($this->err == 'showerror') || $this->phpThumbDebug) {
				// fall through and actually show error message even if default error image is set
			} else {
				header('Location: '.($this->err ? $this->err : $this->config_error_message_image_default));
				exit;
			}
		}
		$this->setOutputFormat();
		if (!$this->thumbnailFormat || !$this->config_disable_debug || (phpthumb_functions::gd_version() < 1)) {
			$this->thumbnailFormat = 'text';
		}
		if (@$this->thumbnailFormat == 'text') {
			// bypass all GD functions and output text error message
			if (!headers_sent()) {
				header('Content-type: text/plain');
				echo $text;
			} else {
				echo '<pre>'.htmlspecialchars($text).'</pre>';
			}
			exit;
		}

		$FontWidth  = ImageFontWidth($this->config_error_fontsize);
		$FontHeight = ImageFontHeight($this->config_error_fontsize);

		$LinesOfText = explode("\n", @wordwrap($text, floor($width / $FontWidth), "\n", true));
		$height = max($height, count($LinesOfText) * $FontHeight);

		$headers_file = '';
		$headers_line = '';
		if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.0', '>=') && headers_sent($headers_file, $headers_line)) {

			echo "\n".'**Headers already sent in file "'.$headers_file.'" on line "'.$headers_line.'", dumping error message as text:**<br><pre>'."\n\n".$text."\n".'</pre>';

		} elseif (headers_sent()) {

			echo "\n".'**Headers already sent, dumping error message as text:**<br><pre>'."\n\n".$text."\n".'</pre>';

		} elseif ($gdimg_error = ImageCreate($width, $height)) {

			$background_color = phpthumb_functions::ImageHexColorAllocate($gdimg_error, $this->config_error_bgcolor,   true);
			$text_color       = phpthumb_functions::ImageHexColorAllocate($gdimg_error, $this->config_error_textcolor, true);
			ImageFilledRectangle($gdimg_error, 0, 0, $width, $height, $background_color);
			$lineYoffset = 0;
			foreach ($LinesOfText as $line) {
				ImageString($gdimg_error, $this->config_error_fontsize, 2, $lineYoffset, $line, $text_color);
				$lineYoffset += $FontHeight;
			}
			if (function_exists('ImageTypes')) {
				$imagetypes = ImageTypes();
				if ($imagetypes & IMG_PNG) {
					header('Content-Type: image/png');
					ImagePNG($gdimg_error);
				} elseif ($imagetypes & IMG_GIF) {
					header('Content-Type: image/gif');
					ImageGIF($gdimg_error);
				} elseif ($imagetypes & IMG_JPG) {
					header('Content-Type: image/jpeg');
					ImageJPEG($gdimg_error);
				} elseif ($imagetypes & IMG_WBMP) {
					header('Content-Type: image/vnd.wap.wbmp');
					ImageWBMP($gdimg_error);
				}
			}
			ImageDestroy($gdimg_error);

		}
		if (!headers_sent()) {
			echo "\n".'**Failed to send graphical error image, dumping error message as text:**<br>'."\n\n".$text;
		}
		exit;
		return true;
	}

	function ImageCreateFromStringReplacement(&$RawImageData, $DieOnErrors=false) {
		// there are serious bugs in the non-bundled versions of GD which may cause
		// PHP to segfault when calling ImageCreateFromString() - avoid if at all possible
		// when not using a bundled version of GD2
		if (!phpthumb_functions::gd_version()) {
			if ($DieOnErrors) {
				if (!headers_sent()) {
					// base64-encoded error image in GIF format
					$ERROR_NOGD = 'R0lGODlhIAAgALMAAAAAABQUFCQkJDY2NkZGRldXV2ZmZnJycoaGhpSUlKWlpbe3t8XFxdXV1eTk5P7+/iwAAAAAIAAgAAAE/vDJSau9WILtTAACUinDNijZtAHfCojS4W5H+qxD8xibIDE9h0OwWaRWDIljJSkUJYsN4bihMB8th3IToAKs1VtYM75cyV8sZ8vygtOE5yMKmGbO4jRdICQCjHdlZzwzNW4qZSQmKDaNjhUMBX4BBAlmMywFSRWEmAI6b5gAlhNxokGhooAIK5o/pi9vEw4Lfj4OLTAUpj6IabMtCwlSFw0DCKBoFqwAB04AjI54PyZ+yY3TD0ss2YcVmN/gvpcu4TOyFivWqYJlbAHPpOntvxNAACcmGHjZzAZqzSzcq5fNjxFmAFw9iFRunD1epU6tsIPmFCAJnWYE0FURk7wJDA0MTKpEzoWAAskiAAA7';
					header('Content-Type: image/gif');
					echo base64_decode($ERROR_NOGD);
				} else {
					echo '*** ERROR: No PHP-GD support available ***';
				}
				exit;
			} else {
				$this->DebugMessage('ImageCreateFromStringReplacement() failed: gd_version says "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
				return false;
			}
		}
		if (phpthumb_functions::gd_is_bundled()) {
			$this->DebugMessage('ImageCreateFromStringReplacement() calling built-in ImageCreateFromString()', __FILE__, __LINE__);
			return @ImageCreateFromString($RawImageData);
		}
		if ($this->issafemode) {
			$this->DebugMessage('ImageCreateFromStringReplacement() failed: cannot create temp file in SAFE_MODE', __FILE__, __LINE__);
			return false;
		}

		switch (substr($RawImageData, 0, 3)) {
			case 'GIF':
				$ICFSreplacementFunctionName = 'ImageCreateFromGIF';
				break;
			case "\xFF\xD8\xFF":
				$ICFSreplacementFunctionName = 'ImageCreateFromJPEG';
				break;
			case "\x89".'PN':
				$ICFSreplacementFunctionName = 'ImageCreateFromPNG';
				break;
			default:
				$this->DebugMessage('ImageCreateFromStringReplacement() failed: unknown fileformat signature "'.phpthumb_functions::HexCharDisplay(substr($RawImageData, 0, 3)).'"', __FILE__, __LINE__);
				return false;
				break;
		}
		if ($tempnam = $this->phpThumb_tempnam()) {
			if ($fp_tempnam = @fopen($tempnam, 'wb')) {
				fwrite($fp_tempnam, $RawImageData);
				fclose($fp_tempnam);
				if (($ICFSreplacementFunctionName == 'ImageCreateFromGIF') && !function_exists($ICFSreplacementFunctionName)) {

					// Need to create from GIF file, but ImageCreateFromGIF does not exist
					ob_start();
					if (!@include_once(dirname(__FILE__).'/phpthumb.gif.php')) {
						$ErrorMessage = 'Failed to include required file "'.dirname(__FILE__).'/phpthumb.gif.php" in '.__FILE__.' on line '.__LINE__;
						$this->DebugMessage($ErrorMessage, __FILE__, __LINE__);
					}
					ob_end_clean();
					// gif_loadFileToGDimageResource() cannot read from raw data, write to file first
					if ($tempfilename = $this->phpThumb_tempnam()) {
						if ($fp_tempfile = @fopen($tempfilename, 'wb')) {
							fwrite($fp_tempfile, $RawImageData);
							fclose($fp_tempfile);
							$gdimg_source = gif_loadFileToGDimageResource($tempfilename);
							$this->DebugMessage('gif_loadFileToGDimageResource('.$tempfilename.') completed', __FILE__, __LINE__);
							$this->DebugMessage('deleting "'.$tempfilename.'"', __FILE__, __LINE__);
							unlink($tempfilename);
							return $gdimg_source;
						} else {
							$ErrorMessage = 'Failed to open tempfile in '.__FILE__.' on line '.__LINE__;
							$this->DebugMessage($ErrorMessage, __FILE__, __LINE__);
						}
					} else {
						$ErrorMessage = 'Failed to open generate tempfile name in '.__FILE__.' on line '.__LINE__;
						$this->DebugMessage($ErrorMessage, __FILE__, __LINE__);
					}

				} elseif (function_exists($ICFSreplacementFunctionName) && ($gdimg_source = @$ICFSreplacementFunctionName($tempnam))) {

					// great
					$this->DebugMessage($ICFSreplacementFunctionName.'('.$tempnam.') succeeded', __FILE__, __LINE__);
					$this->DebugMessage('deleting "'.$tempnam.'"', __FILE__, __LINE__);
					unlink($tempnam);
					return $gdimg_source;

				} else {

					// GD functions not available, or failed to create image
					$this->DebugMessage($ICFSreplacementFunctionName.'('.$tempnam.') '.(function_exists($ICFSreplacementFunctionName) ? 'failed' : 'does not exist'), __FILE__, __LINE__);
					if (isset($_GET['phpThumbDebug'])) {
						$this->phpThumbDebug();
					}

				}
			} else {
				$ErrorMessage = 'Failed to fopen('.$tempnam.', "wb") in '.__FILE__.' on line '.__LINE__."\n".'You may need to set $PHPTHUMB_CONFIG[temp_directory] in phpThumb.config.php';
				if ($this->issafemode) {
					$ErrorMessage = 'ImageCreateFromStringReplacement() failed in '.__FILE__.' on line '.__LINE__.': cannot create temp file in SAFE_MODE';
				}
				$this->DebugMessage($ErrorMessage, __FILE__, __LINE__);
			}
			$this->DebugMessage('deleting "'.$tempnam.'"', __FILE__, __LINE__);
			@unlink($tempnam);
		} else {
			$ErrorMessage = 'Failed to generate phpThumb_tempnam() in '.__FILE__.' on line '.__LINE__."\n".'You may need to set $PHPTHUMB_CONFIG[temp_directory] in phpThumb.config.php';
			if ($this->issafemode) {
				$ErrorMessage = 'ImageCreateFromStringReplacement() failed in '.__FILE__.' on line '.__LINE__.': cannot create temp file in SAFE_MODE';
			}
		}
		if ($DieOnErrors && $ErrorMessage) {
			return $this->ErrorImage($ErrorMessage);
		}
		return false;
	}

	function ImageResizeFunction(&$dst_im, &$src_im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH) {
		$this->DebugMessage('ImageResizeFunction($o, $s, '.$dstX.', '.$dstY.', '.$srcX.', '.$srcY.', '.$dstW.', '.$dstH.', '.$srcW.', '.$srcH.')', __FILE__, __LINE__);
		if (($dstW == $srcW) && ($dstH == $srcH)) {
			return ImageCopy($dst_im, $src_im, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH);
		}
		if (phpthumb_functions::gd_version() >= 2.0) {
			if ($this->config_disable_imagecopyresampled) {
				return phpthumb_functions::ImageCopyResampleBicubic($dst_im, $src_im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
			}
			return ImageCopyResampled($dst_im, $src_im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
		}
		return ImageCopyResized($dst_im, $src_im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
	}

	function InitializeTempDirSetting() {
		$this->config_temp_directory = realpath($this->config_temp_directory ? $this->config_temp_directory : (getenv('TMPDIR') ? getenv('TMPDIR') : getenv('TMP')));
		return true;
	}

	function phpThumb_tempnam() {
		$this->InitializeTempDirSetting();
		$tempnam = realpath(tempnam($this->config_temp_directory, 'pThumb'));
		$this->tempFilesToDelete[$tempnam] = $tempnam;
		$this->DebugMessage('phpThumb_tempnam() returning "'.$tempnam.'"', __FILE__, __LINE__);
		return $tempnam;
	}

	function DebugMessage($message, $file='', $line='') {
		$this->debugmessages[] = $message.($file ? ' in file "'.(basename($file) ? basename($file) : $file).'"' : '').($line ? ' on line '.$line : '');
		return true;
	}

	function DebugTimingMessage($message, $file='', $line='', $timestamp=0) {
		if (!$timestamp) {
			$timestamp = array_sum(explode(' ', microtime()));
		}
		$this->debugtiming[number_format($timestamp, 6, '.', '')] = ': '.$message.($file ? ' in file "'.(basename($file) ? basename($file) : $file).'"' : '').($line ? ' on line '.$line : '');
		return true;
	}

}
