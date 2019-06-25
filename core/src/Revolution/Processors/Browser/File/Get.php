<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Browser\File;


use MODX\Revolution\Processors\Browser\Browser;

/**
 * Gets the contents of a file
 *
 * @property string $file The absolute path of the file
 *
 * @package MODX\Revolution\Processors\Browser\File
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
