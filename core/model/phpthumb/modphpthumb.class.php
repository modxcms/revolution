<?php
/**
 * @package modx
 * @subpackage phpthumb
 */
require_once MODX_CORE_PATH.'model/phpthumb/phpthumb.class.php';
/**
 * Helper class to extend phpThumb and simplify thumbnail generation process
 * since phpThumb class is overly convoluted and doesn't do enough.
 *
 * @package modx
 * @subpackage phpthumb
 */
class modPhpThumb extends phpThumb {

    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(

        ),$config);
        parent::__construct();
    }

    /**
     * Setup some site-wide phpthumb options from modx config
     */
    public function initialize() {
        $cachePath = $this->modx->getOption('core_path',null,MODX_CORE_PATH).'cache/phpthumb/';
        if (!is_dir($cachePath)) $this->modx->cacheManager->writeTree($cachePath);
        $this->setParameter('config_cache_directory',$cachePath);
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
        $this->setParameter('cache_source_enabled',(boolean)$this->modx->getOption('phpthumb_cache_source_enabled',$this->config,false));
        $this->setParameter('cache_source_directory',$cachePath.'source/');
        $this->setParameter('allow_local_http_src',true);
        $this->setParameter('zc',$this->modx->getOption('zc',$_REQUEST,$this->modx->getOption('phpthumb_zoomcrop',$this->config,0)));
        $this->setParameter('far',$this->modx->getOption('far',$_REQUEST,$this->modx->getOption('phpthumb_far',$this->config,'C')));
        $this->setParameter('cache_directory_depth',4);
        $this->setParameter('config_ttf_directory',$this->modx->getOption('core_path',$this->config,MODX_CORE_PATH).'model/phpthumb/fonts/');
        
        $documentRoot = $this->modx->getOption('phpthumb_document_root',$this->config,'');
        if (!empty($documentRoot)) {
            $this->setParameter('config_document_root',$documentRoot);
        }

        /* iterate through properties */
        foreach ($this->config as $property => $value) {
            $this->setParameter($property,$value);
        }
        return true;
    }

    /**
     * Sets the source image
     */
    public function set($src) {
        $src = rawurldecode($src);
        if (empty($src)) return '';
        return $this->setSourceFilename($src);
    }

    /**
     * Check to see if cached file already exists
     */
    public function checkForCachedFile() {
        $this->setCacheFilename();
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
            $this->modx->log(modx::LOG_LEVEL_ERROR,'Error outputting thumbnail:'."\n".$this->debugmessages[(count($this->debugmessages) - 1)]);
        }
        return $output;
    }


    /** PHPTHUMB HELPER METHODS **/

    public function RedirectToCachedFile() {

        $nice_cachefile = str_replace(DIRECTORY_SEPARATOR, '/', $this->cache_filename);
        $nice_docroot   = str_replace(DIRECTORY_SEPARATOR, '/', rtrim($this->config_document_root, '/\\'));

        $parsed_url = phpthumb_functions::ParseURLbetter(@$_SERVER['HTTP_REFERER']);

        $nModified  = filemtime($this->cache_filename);

        if ($this->config_nooffsitelink_enabled && @$_SERVER['HTTP_REFERER'] && !in_array(@$parsed_url['host'], $this->config_nooffsitelink_valid_domains)) {

            $this->DebugMessage('Would have used cached (image/'.$this->thumbnailFormat.') file "'.$this->cache_filename.'" (Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT), but skipping because $_SERVER[HTTP_REFERER] ('.@$_SERVER['HTTP_REFERER'].') is not in $this->config_nooffsitelink_valid_domains ('.implode(';', $this->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);

        } elseif ($this->phpThumbDebug) {

            $this->DebugTimingMessage('skipped using cached image', __FILE__, __LINE__);
            $this->DebugMessage('Would have used cached file, but skipping due to phpThumbDebug', __FILE__, __LINE__);
            $this->DebugMessage('* Would have sent headers (1): Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT', __FILE__, __LINE__);
            $getimagesize = @GetImageSize($this->cache_filename);
            if ($getimagesize) {
                $this->DebugMessage('* Would have sent headers (2): Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]), __FILE__, __LINE__);
            }
            if (ereg('^'.preg_quote($nice_docroot).'(.*)$', $nice_cachefile, $matches)) {
                $this->DebugMessage('* Would have sent headers (3): Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])), __FILE__, __LINE__);
            } else {
                $this->DebugMessage('* Would have sent data: readfile('.$this->cache_filename.')', __FILE__, __LINE__);
            }

        } else {
/*
            if (headers_sent()) {
                $this->ErrorImage('Headers already sent ('.basename(__FILE__).' line '.__LINE__.')');
                exit;
            }*/
            $this->SendSaveAsFileHeaderIfNeeded();

            header('Last-Modified: '.gmdate('D, d M Y H:i:s', $nModified).' GMT');
            if (@$_SERVER['HTTP_IF_MODIFIED_SINCE'] && ($nModified == strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) && @$_SERVER['SERVER_PROTOCOL']) {
                header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
                exit;
            }

            $getimagesize = @GetImageSize($this->cache_filename);
            if ($getimagesize) {
                header('Content-Type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]));
            } elseif (eregi('\.ico$', $this->cache_filename)) {
                header('Content-Type: image/x-icon');
            }
            if (!$this->config_cache_force_passthru && ereg('^'.preg_quote($nice_docroot).'(.*)$', $nice_cachefile, $matches)) {
                header('Location: '.dirname($matches[1]).'/'.urlencode(basename($matches[1])));
            } else {
                @readfile($this->cache_filename);
            }
            exit;

        }
        return true;
    }
    public function SendSaveAsFileHeaderIfNeeded() {
        if (headers_sent()) {
            return false;
        }
        $downloadfilename = phpthumb_functions::SanitizeFilename(@$_GET['sia'] ? $_GET['sia'] : (@$_GET['down'] ? $_GET['down'] : 'phpThumb_generated_thumbnail'.(@$_GET['f'] ? $_GET['f'] : 'jpg')));
        if (@$downloadfilename) {
            $this->DebugMessage('SendSaveAsFileHeaderIfNeeded() sending header: Content-Disposition: '.(@$_GET['down'] ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"', __FILE__, __LINE__);
            header('Content-Disposition: '.(@$_GET['down'] ? 'attachment' : 'inline').'; filename="'.$downloadfilename.'"');
        }
        return true;
    }
}
