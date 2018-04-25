<?php

/**
 * Set visibility on a directory or file
 *
 * @param string $mode The mode to chmod to
 * @param string $dir The absolute path of the dir
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
require_once dirname(__FILE__) . '/browser.class.php';

class modBrowserVisibilityProcessor extends modBrowserProcessor
{
    public $permission = 'directory_chmod';
    public $policy = 'save';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $visibility = $this->getProperty('visibility');
        if (empty($visibility)) {
            $this->addFieldError('visibility', $this->modx->lexicon('file_folder_visibility_err_ns'));
        }
        $path = $this->sanitize($this->getProperty('path'));
        if (empty($path)) {
            $this->addFieldError('path', $this->modx->lexicon('file_folder_err_ns'));
        }
        if ($this->hasErrors()) {
            return $this->failure();
        }
        $response = $this->source->setVisibility($path, $visibility);

        return $this->handleResponse($response);
    }
}

return 'modBrowserVisibilityProcessor';
