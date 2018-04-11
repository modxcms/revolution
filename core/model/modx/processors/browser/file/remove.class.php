<?php

/**
 * Removes a file.
 *
 * @param string $file The name of the file.
 * @param boolean $prependPath If true, will prepend the rb_base_dir to the file
 * name.
 *
 * @package modx
 * @subpackage processors.browser.file
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFileRemoveProcessor extends modBrowserProcessor
{
    public $permission = 'file_remove';
    public $policy = 'remove';
    public $languageTopics = ['file'];


    /**
     * @return array|bool|mixed|string
     */
    public function process()
    {
        $file = rawurldecode($this->getProperty('file'));
        if (empty($file)) {
            return $this->failure($this->modx->lexicon('file_err_ns'));
        }
        $response = $this->source->removeObject($file);

        return $this->handleResponse($response);
    }
}

return 'modBrowserFileRemoveProcessor';
