<?php
/**
 * Generate a thumbnail
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 * 
 * @package modx
 * @subpackage processors.system
 */
class modSystemPhpThumbProcessor extends modProcessor {
    /** @var modPhpThumb $phpThumb */
    public $phpThumb;
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;

    public function initialize() {
        $this->setDefaultProperties(array(
            'wctx' => $this->modx->context->get('key'),
            'src' => '',
            'source' => 1,
        ));
        $this->modx->getService('fileHandler','modFileHandler','',array(
            'context' => $this->getProperty('wctx')
        ));
        error_reporting(E_ALL);
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return mixed
     */
    public function process() {
        $src = $this->getProperty('src');
        if (empty($src)) return $this->failure();

        $this->unsetProperty('src');

        $this->getSource($this->getProperty('source'));
        if (empty($this->source)) $this->failure($this->modx->lexicon('source_err_nf'));

        $src = $this->source->prepareSrcForThumb($src);
        if (empty($src)) return '';

        $this->loadPhpThumb();
        /* set source and generate thumbnail */
        $this->phpThumb->set($src);

        /* check to see if there's a cached file of this already */
        if ($this->phpThumb->checkForCachedFile()) {
            $this->phpThumb->loadCache();
            return '';
        }

        /* generate thumbnail */
        $this->phpThumb->generate();

        /* cache the thumbnail and output */
        $this->phpThumb->cache();
        $this->phpThumb->output();
        return '';
    }

    /**
     * Get the source to load the paths from
     * 
     * @param int $sourceId
     * @return modMediaSource|modFileMediaSource
     */
    public function getSource($sourceId) {
        /** @var modMediaSource|modFileMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx,$sourceId,false);
        if (empty($this->source)) return false;

        if (!$this->source->getWorkingContext()) {
            return false;
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();
        return $this->source;
    }

    /**
     * Attempt to load modPhpThumb
     * 
     * @return bool|modPhpThumb
     */
    public function loadPhpThumb() {
        if (!$this->modx->loadClass('modPhpThumb',$this->modx->getOption('core_path').'model/phpthumb/',true,true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'Could not load modPhpThumb class.');
            return false;
        }
        $this->phpThumb = new modPhpThumb($this->modx,$this->getProperties());
        /* do initial setup */
        $this->phpThumb->initialize();

        return $this->phpThumb;
    }
}
return 'modSystemPhpThumbProcessor';