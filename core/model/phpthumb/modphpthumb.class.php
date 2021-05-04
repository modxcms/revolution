<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once MODX_CORE_PATH . 'model/phpthumb/phpthumb.class.php';

/**
 * Helper class to extend phpThumb and simplify thumbnail generation process
 * since phpThumb class is overly convoluted and doesn't do enough.
 *
 * @package modx
 * @subpackage phpthumb
 */
class modPhpThumb extends phpThumb
{
    public $modx;

    public $config = array();

    /**
     * modPhpThumb constructor.
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;
        $this->config = $config;

        if (version_compare(PHP_VERSION, '5.6.25', '<')
            || (version_compare(PHP_VERSION, '7.0.0', '>=')
                && version_compare(PHP_VERSION, '7.0.10', '<'))) {
            // The constant IMG_WEBP is available as of PHP 5.6.25 and PHP 7.0.10, respectively.
            define('IMG_WEBP', 32);
        }

        parent::__construct();
    }

    /**
     * Setup some site-wide phpthumb options from modx config
     */
    public function initialize()
    {
        $cachePath = $this->modx->getOption('core_path',null,MODX_CORE_PATH).'cache/phpthumb/';
        if (!is_dir($cachePath)) {
            $this->modx->cacheManager->writeTree($cachePath);
        }
        $this->setParameter('config_cache_directory', $cachePath);
        $this->setParameter('config_temp_directory', $cachePath);
        $this->setCacheDirectory();

        $this->setParameter('config_allow_src_above_docroot',(boolean)$this->modx->getOption('phpthumb_allow_src_above_docroot',$this->config,false));
        $this->setParameter('config_cache_maxage',(float)$this->modx->getOption('phpthumb_cache_maxage',$this->config,30) * 86400);
        $this->setParameter('config_cache_maxsize',(float)$this->modx->getOption('phpthumb_cache_maxsize',$this->config,100) * 1024 * 1024);
        $this->setParameter('config_cache_maxfiles',(int)$this->modx->getOption('phpthumb_cache_maxfiles',$this->config,10000));
        $this->setParameter('config_error_bgcolor',(string)$this->modx->getOption('phpthumb_error_bgcolor',$this->config,'CCCCFF'));
        $this->setParameter('config_error_textcolor',(string)$this->modx->getOption('phpthumb_error_textcolor',$this->config,'FF0000'));
        $this->setParameter('config_error_fontsize',(int)$this->modx->getOption('phpthumb_error_fontsize',$this->config,1));
        $this->setParameter('config_nohotlink_enabled',(boolean)$this->modx->getOption('phpthumb_nohotlink_enabled',$this->config,true));
        $this->setParameter('config_nohotlink_valid_domains',explode(',', $this->modx->getOption('phpthumb_nohotlink_valid_domains',$this->config,$this->modx->getOption('http_host'))));
        $this->setParameter('config_nohotlink_erase_image',(boolean)$this->modx->getOption('phpthumb_nohotlink_erase_image',$this->config,true));
        $this->setParameter('config_nohotlink_text_message',(string)$this->modx->getOption('phpthumb_nohotlink_text_message',$this->config,'Off-server thumbnailing is not allowed'));
        $this->setParameter('config_nooffsitelink_enabled',(boolean)$this->modx->getOption('phpthumb_nooffsitelink_enabled',$this->config,false));
        $this->setParameter('config_nooffsitelink_valid_domains',explode(',', $this->modx->getOption('phpthumb_nooffsitelink_valid_domains',$this->config,$this->modx->getOption('http_host'))));
        $this->setParameter('config_nooffsitelink_require_refer',(boolean)$this->modx->getOption('phpthumb_nooffsitelink_require_refer',$this->config,false));
        $this->setParameter('config_nooffsitelink_erase_image',(boolean)$this->modx->getOption('phpthumb_nooffsitelink_erase_image',$this->config,true));
        $this->setParameter('config_nooffsitelink_watermark_src',(string)$this->modx->getOption('phpthumb_nooffsitelink_watermark_src',$this->config,''));
        $this->setParameter('config_nooffsitelink_text_message',(string)$this->modx->getOption('phpthumb_nooffsitelink_text_message',$this->config,'Off-server linking is not allowed'));
        $this->setParameter('config_ttf_directory', (string)$this->modx->getOption('core_path', $this->config, MODX_CORE_PATH) . 'model/phpthumb/fonts/');
        $this->setParameter('config_imagemagick_path', (string)$this->modx->getOption('phpthumb_imagemagick_path', $this->config, null));

        $this->setParameter('cache_source_enabled',(boolean)$this->modx->getOption('phpthumb_cache_source_enabled',$this->config,false));
        $this->setParameter('cache_source_directory',$cachePath.'source/');
        $this->setParameter('allow_local_http_src',true);
        $this->setParameter('zc',$this->modx->getOption('zc',$_REQUEST,$this->modx->getOption('phpthumb_zoomcrop',$this->config,0)));
        $this->setParameter('far',$this->modx->getOption('far',$_REQUEST,$this->modx->getOption('phpthumb_far',$this->config,'C')));
        $this->setParameter('cache_directory_depth',4);

        $documentRoot = $this->modx->getOption('phpthumb_document_root',$this->config, '');
        if ($documentRoot == '') $documentRoot = $this->modx->getOption('base_path', null, '');
        if (!empty($documentRoot)) {
            $this->setParameter('config_document_root',$documentRoot);
        }

        // Only public parameters of phpThumb should be allowed to pass from user input.
        // List properties between START PARAMETERS and START PARAMETERS in src/core/model/phpthumb/phpthumb.class.php
        $allowed = array(
            'src', 'new', 'w', 'h', 'wp', 'hp', 'wl', 'hl', 'ws', 'hs',
            'f', 'q', 'sx', 'sy', 'sw', 'sh', 'zc', 'bc', 'bg', 'fltr',
            'goto', 'err', 'xto', 'ra', 'ar', 'aoe', 'far', 'iar', 'maxb', 'down',
            'md5s', 'sfn', 'dpi', 'sia', 'phpThumbDebug'
        );

        /* iterate through properties */
        foreach ($this->config as $property => $value) {
            if (!in_array($property, $allowed, true)) {
                $this->modx->log(modX::LOG_LEVEL_WARN,"Detected attempt of using private parameter `$property` (for internal usage) of phpThumb that not allowed and insecure");
                continue;
            }
            $this->setParameter($property, $value);
        }

        return true;
    }

    /**
     * Sets the source image
     */
    public function set($src) {
        $src = rawurldecode($src);
        if (empty($src)) return '';

        // Detecting URL's and explicitly replacing spaces with %20 for phpThumb to work correctly
        if (preg_match('#^[a-z0-9]+://#i', $src)) {
			$src = str_replace(' ', '%20', $src);
        }

        return $this->setSourceFilename($src);
    }

    /**
     * Check to see if cached file already exists
     */
    public function checkForCachedFile() {
        $this->SetCacheFilename();
        if (file_exists($this->cache_filename) && is_readable($this->cache_filename)) {
            return true;
        }
        return false;
    }

    /**
     * Load cached file
     */
    public function loadCache() {
        $this->RedirectToCachedFile();
    }

    /**
     * Cache the generated thumbnail.
     */
    public function cache() {
        phpthumb_functions::EnsureDirectoryExists(dirname($this->cache_filename));
        if ((file_exists($this->cache_filename) && is_writable($this->cache_filename)) || is_writable(dirname($this->cache_filename))) {
            $this->CleanUpCacheDirectory();
            if ($this->RenderToFile($this->cache_filename) && is_readable($this->cache_filename)) {
                chmod($this->cache_filename, 0644);
                $this->RedirectToCachedFile();
            }
        }
    }

    /**
     * Generate a thumbnail
     */
    public function generate() {
        if (!$this->GenerateThumbnail()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'phpThumb was unable to generate a thumbnail for: '.$this->cache_filename);
            return false;
        }
        return true;
    }

    /**
     * Output a thumbnail.
     */
    public function output() {
        $output = $this->OutputThumbnail();
        if (!$output) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'Error outputting thumbnail:'."\n".$this->debugmessages[(count($this->debugmessages) - 1)]);
        }
        return $output;
    }


    /** PHPTHUMB HELPER METHODS **/

    public function RedirectToCachedFile() {

        $nice_cachefile = str_replace(DIRECTORY_SEPARATOR, '/', $this->cache_filename);
        $nice_docroot   = str_replace(DIRECTORY_SEPARATOR, '/', rtrim($this->config_document_root, '/\\'));

        $parsed_url = phpthumb_functions::ParseURLbetter(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

        $nModified  = filemtime($this->cache_filename);

        if ($this->config_nooffsitelink_enabled && !empty($_SERVER['HTTP_REFERER']) && !in_array(isset($parsed_url['host']) ? $parsed_url['host'] : '', $this->config_nooffsitelink_valid_domains)) {

            $this->DebugMessage('Would have used cached (image/'.$this->thumbnailFormat.') file "'.$this->cache_filename.'" (Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT), but skipping because $_SERVER[HTTP_REFERER] ('.$_SERVER['HTTP_REFERER'].') is not in $this->config_nooffsitelink_valid_domains ('.implode(';', $this->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);

        } elseif ($this->phpThumbDebug) {

            $this->DebugTimingMessage('skipped using cached image', __FILE__, __LINE__);
            $this->DebugMessage('Would have used cached file, but skipping due to phpThumbDebug', __FILE__, __LINE__);
            $this->DebugMessage('* Would have sent headers (1): Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT', __FILE__, __LINE__);
            try {
                $getimagesize = getimagesize($this->cache_filename);
                if ($getimagesize) {
                    $this->DebugMessage('* Would have sent headers (2): Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]), __FILE__, __LINE__);
                }
                if (preg_match('/^'.preg_quote($nice_docroot, '/').'(.*)$/', $nice_cachefile, $matches)) {
                    $this->DebugMessage('* Would have sent headers (3): Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])), __FILE__, __LINE__);
                } else {
                    $this->DebugMessage('* Would have sent data: readfile('.$this->cache_filename.')', __FILE__, __LINE__);
                }
            }
            catch(Exception $Exception) {
                $this->DebugMessage('getimagesize() failed for "'.$this->cache_filename.'" with message: '.$Exception->getMessage(), __FILE__, __LINE__);
            }

        } else {
/*
            if (headers_sent()) {
                $this->ErrorImage('Headers already sent ('.basename(__FILE__).' line '.__LINE__.')');
                exit;
            }*/
            $this->SendSaveAsFileHeaderIfNeeded();

            header('Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT');
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && ($nModified == strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) && isset($_SERVER['SERVER_PROTOCOL'])) {
                header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
                exit;
            }

            try {
                $getimagesize = getimagesize($this->cache_filename);
                if ($getimagesize) {
                    header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]));
                } elseif (preg_match('#\.ico$#i', $this->cache_filename)) {
                    header('Content-Type: image/x-icon');
                }
                if (!$this->config_cache_force_passthru && preg_match('#^'.preg_quote($nice_docroot, '/').'(.*)$#', $nice_cachefile, $matches)) {
                    header('Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])));
                } else {
                    readfile($this->cache_filename);
                }
            }
            catch(Exception $Exception) {
                $this->DebugMessage('getimagesize() failed for "'.$this->cache_filename.'" with message: '.$Exception->getMessage(), __FILE__, __LINE__);
            }
            finally {
                session_write_close();
                exit;
            }

        }
        return true;
    }
    public function SendSaveAsFileHeaderIfNeeded() {
        if (headers_sent()) {
            return false;
        }
        $downloadfilename = phpthumb_functions::SanitizeFilename(isset($_GET['sia']) ? $_GET['sia'] : (isset($_GET['down']) ? $_GET['down'] : 'phpThumb_generated_thumbnail'.(isset($_GET['f']) ? $_GET['f'] : 'jpg')));
        if ($downloadfilename) {
            $this->DebugMessage('SendSaveAsFileHeaderIfNeeded() sending header: Content-Disposition: '.(isset($_GET['down']) ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"', __FILE__, __LINE__);
            header('Content-Disposition: '.(isset($_GET['down']) ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"');
        }
        return true;
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

        } elseif ($this->iswindows && substr($filename, 1, 1) == ':') {

            // absolute pathname (Windows)
            $AbsoluteFilename = $filename;

        } elseif ($this->iswindows && ((substr($filename, 0, 2) == '//') || (substr($filename, 0, 2) == '\\\\'))) {

            // absolute pathname (Windows)
            $AbsoluteFilename = $filename;

        } elseif (substr($filename, 0, 1) == '/') {

            if (is_readable($filename) && !is_readable($this->config_document_root.$filename)) {

                // absolute filename (*nix)
                $AbsoluteFilename = $filename;

            } elseif (substr($filename, 1, 1) == '~') {

                // /~user/path
                if ($ApacheLookupURIarray = phpthumb_functions::ApacheLookupURIarray($filename)) {
                    $AbsoluteFilename = $ApacheLookupURIarray['filename'];
                } else {
                    $AbsoluteFilename = realpath($filename);
                    if (is_readable($AbsoluteFilename)) {
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
            $AbsoluteFilename = $this->config_document_root.(isset($_SERVER['PHP_SELF']) ? preg_replace('#[/\\\\]#', DIRECTORY_SEPARATOR, dirname($_SERVER['PHP_SELF'])) : '').DIRECTORY_SEPARATOR.preg_replace('#[/\\\\]#', DIRECTORY_SEPARATOR, $filename);
//			$AbsoluteFilename = dirname(__FILE__).DIRECTORY_SEPARATOR.preg_replace('#[/\\\\]#', DIRECTORY_SEPARATOR, $filename);

            $AbsoluteFilename = preg_replace('~[\/]+~', DIRECTORY_SEPARATOR, $AbsoluteFilename);

            //if (!file_exists($AbsoluteFilename) && file_exists(realpath($this->DotPadRelativeDirectoryPath($filename)))) {
            //	$AbsoluteFilename = realpath($this->DotPadRelativeDirectoryPath($filename));
            //}

            if (isset($_SERVER['PHP_SELF']) && substr(dirname($_SERVER['PHP_SELF']), 0, 2) == '/~') {
                if ($ApacheLookupURIarray = phpthumb_functions::ApacheLookupURIarray(dirname($_SERVER['PHP_SELF']))) {
                    $AbsoluteFilename = $ApacheLookupURIarray['filename'].DIRECTORY_SEPARATOR.$filename;
                } else {
                    $AbsoluteFilename = realpath('.').DIRECTORY_SEPARATOR.$filename;
                    if (is_readable($AbsoluteFilename)) {
                        $this->DebugMessage('phpthumb_functions::ApacheLookupURIarray() failed for "'.dirname($_SERVER['PHP_SELF']).'", but the correct filename ('.$AbsoluteFilename.') seems to have been resolved with realpath(.)/$filename', __FILE__, __LINE__);
                    } elseif (is_dir(dirname($AbsoluteFilename))) {
                        $this->DebugMessage('phpthumb_functions::ApacheLookupURIarray() failed for "'.dirname($_SERVER['PHP_SELF']).'", but the correct directory ('.dirname($AbsoluteFilename).') seems to have been resolved with realpath(.)', __FILE__, __LINE__);
                    } else {
                        return $this->ErrorImage('phpthumb_functions::ApacheLookupURIarray() failed for "'.dirname($_SERVER['PHP_SELF']).'". This has been known to fail on Apache2 - try using the absolute filename for the source image');
                    }
                }
            }

        }
        if (is_link($AbsoluteFilename)) {
            $this->DebugMessage('is_link()==true, changing "'.$AbsoluteFilename.'" to "'.readlink($AbsoluteFilename).'"', __FILE__, __LINE__);
            $AbsoluteFilename = readlink($AbsoluteFilename);
        }
        if (realpath($AbsoluteFilename)) {
            $AbsoluteFilename = realpath($AbsoluteFilename);
        }
        if ($this->iswindows) {
            $AbsoluteFilename = preg_replace('#^'.preg_quote(realpath($this->config_document_root)).'#i', realpath($this->config_document_root), $AbsoluteFilename);
            $AbsoluteFilename = str_replace(DIRECTORY_SEPARATOR, '/', $AbsoluteFilename);
        }
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
}

