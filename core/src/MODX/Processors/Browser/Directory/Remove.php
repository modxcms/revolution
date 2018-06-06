<?php

namespace MODX\Processors\Browser\Directory;

use MODX\Processors\Browser\Browser;

/**
 * Remove a directory
 *
 * @param string $dir The directory to remove
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
class Remove extends Browser
{
    public $permission = 'directory_remove';
    public $policy = 'remove';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $dir = $this->sanitize($this->getProperty('dir'));
        if (empty($dir)) {
            return $this->failure($this->modx->lexicon('file_folder_err_ns'));
        }
        $response = $this->source->removeContainer($dir);

        return $this->handleResponse($response);
    }
}
