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


use Exception;
use MODX\Revolution\Processors\Browser\Browser;

/**
 * Send a file to user
 *
 * @property string $file The absolute path of the file
 *
 * @package MODX\Revolution\Processors\Browser\File
 */
class Download extends Browser
{
    public $permission = 'file_view';
    public $policy = 'view';
    public $languageTopics = ['file'];


    /**
     * @return array|bool|mixed|string
     */
    public function process()
    {
        $file = $this->sanitize($this->getProperty('file', ''));
        if (empty($file)) {
            return $this->failure($this->modx->lexicon('file_err_ns'));
        }

        // Manager asks for file url
        if (!$this->getProperty('download')) {
            return $this->success('', ['url' => rawurlencode($this->source->getObjectUrl($file))]);
        }

        // Download file
        @session_write_close();
        $file = $this->sanitize($this->getProperty('file'));
        try {
            if ($data = $this->source->getObjectContents($file)) {
                $name = preg_replace('#[^\w-.]#ui', '_', $data['basename']);
                header('Content-type: ' . $data['mime']);
                header('Content-Length: ' . $data['size']);
                header('Content-Disposition: attachment; filename=' . $name);

                exit($data['content']);
            } else {
                exit($this->modx->lexicon('file_err_open') . $this->getProperty('file'));
            }
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
}
