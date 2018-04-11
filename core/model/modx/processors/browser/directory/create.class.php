<?php
/**
 * Create a directory.
 *
 * @param string $name The name of the directory to create
 * @param string $parent The parent directory
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFolderCreateProcessor extends modBrowserProcessor
{

    public $permission = 'directory_create';
    public $policy = 'create';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $parent = rawurldecode($this->getProperty('parent', ''));
        $name = rawurldecode($this->getProperty('name'));
        if (empty($name)) {
            return $this->modx->lexicon('file_folder_err_ns_name');
        }
        $response = $this->source->createContainer($name, $parent);

        return $this->handleResponse($response);
    }
}

return 'modBrowserFolderCreateProcessor';
