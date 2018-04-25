<?php

/**
 * Moves a file/directory.
 *
 * @var modX $this ->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFolderSortProcessor extends modBrowserProcessor
{
    public $permission = 'directory_update';
    public $policy = 'save';
    public $languageTopics = ['file', 'source'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        if (!$from = $this->sanitize($this->getProperty('from'))) {
            return $this->failure($this->modx->lexicon('file_folder_err_ns'));
        }
        $to = $this->sanitize($this->getProperty('to'));
        $point = $this->getProperty('point', 'append');
        $dest = (int)$this->getProperty('destSource');

        $response = ($dest && $dest != $this->source->get('id'))
            ? $this->source->moveObject($from, $to, $point, $dest)
            : $this->source->moveObject($from, $to, $point);

        return $this->handleResponse($response);
    }
}

return 'modBrowserFolderSortProcessor';
