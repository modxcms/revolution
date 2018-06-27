<?php

/**
 * Gets all files in a directory
 *
 * @param string $dir The directory to browse
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 * @param boolean $prependUrl (optional) If true, will prepend rb_base_url to
 * the final url
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
require_once dirname(__DIR__) . '/browser.class.php';

class modBrowserFolderGetFilesProcessor extends modBrowserProcessor
{
    public $permission = 'file_list';
    public $policy = 'list';
    public $languageTopics = ['file'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $allowedFileTypes = $this->getProperty('allowedFileTypes');
        if (empty($allowedFileTypes)) {
            // Prevent overriding media source configuration
            unset($this->properties['allowedFileTypes']);
            $this->source->setRequestProperties($this->properties);
        }

        $dir = $this->sanitize($this->getProperty('dir'));
        if ($dir === 'root') {
            $dir = '';
        }
        $list = $this->source->getObjectsInContainer($dir);

        return $this->source->hasErrors()
            ? $this->failure($this->source->getErrors(), [])
            : $this->outputArray($list);
    }
}

return 'modBrowserFolderGetFilesProcessor';
