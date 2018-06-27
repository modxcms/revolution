<?php

/**
 * Get a list of directories and files, sorting them first by folder/file and
 * then alphanumerically.
 *
 * @param string $id The path to grab a list from
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 * @param boolean $hideFiles (optional) If true, will not display files.
 * Defaults to false.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFolderGetListProcessor extends modBrowserProcessor
{
    public $permission = 'directory_list';
    public $policy = 'list';
    public $languageTopics = ['file', 'source'];


    /**
     * @return array|mixed|string]
     */
    public function process()
    {
        $dir = $this->sanitize($this->getProperty('id', ''));
        if ($dir === 'root') {
            $dir = '';
        } elseif (strpos($dir, 'n_') === 0) {
            $dir = substr($dir, 2);
        }
        $list = $this->source->getContainerList($dir);

        return $this->source->hasErrors()
            ? $this->failure($this->source->getErrors(), [])
            : json_encode($list);
    }
}

return 'modBrowserFolderGetListProcessor';
