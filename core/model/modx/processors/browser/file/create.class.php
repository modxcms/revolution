<?php

/**
 * Updates a file.
 *
 * @param string $file The absolute path of the file
 * @param string $name Will rename the file if different
 * @param string $content The new content of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFileCreateProcessor extends modBrowserProcessor
{
    public $permission = 'file_create';
    public $policy = 'create';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $directory = $this->sanitize($this->getProperty('directory', ''));
        $name = $this->sanitize($this->getProperty('name'));
        if (empty($name)) {
            return $this->failure($this->modx->lexicon('file_err_ns'));
        }
        $response = $this->source->createObject($directory, $name, $this->getProperty('content'));

        return empty($response)
            ? $this->handleResponse($response)
            : $this->success('', [
                'file' => rawurlencode($directory . ltrim($name, '/')),
            ]);
    }
}

return 'modBrowserFileCreateProcessor';
