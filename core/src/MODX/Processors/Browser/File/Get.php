<?php

namespace MODX\Processors\Browser\File;

use MODX\Processors\Browser\Browser;

/**
 * Gets the contents of a file
 *
 * @param string $file The absolute path of the file
 *
 * @package modx
 * @subpackage processors.browser.file
 */
class Get extends Browser
{
    public $permission = 'file_view';
    public $languageTopics = ['file'];


    /**
     * @return array|bool|string
     */
    public function initialize()
    {
        if (!$this->getSource() || !$this->source->checkPolicy('delete')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        return true;
    }


    /**
     * @return array|bool|mixed|string
     */
    public function process()
    {
        $file = $this->sanitize($this->getProperty('file'));
        if (empty($file)) {
            return $this->failure($this->modx->lexicon('file_err_ns'));
        }

        $success = $this->source->getObjectContents($file);
        if (empty($success)) {
            $msg = '';
            $errors = $this->source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->addFieldError($k, $msg);
            }

            return $this->failure($msg);
        }

        return $this->success('', $success);
    }
}
