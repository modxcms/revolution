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
 * Unpacks archives, currently only zip
 *
 * @package MODX\Revolution\Processors\Browser\File
 */
class Unpack extends Browser
{
    public $permission = 'file_unpack';
    public $policy = 'view';
    public $languageTopics = ['file'];


    /**
     * @return array|bool|mixed|string
     */
    public function process()
    {
        $file = $this->sanitize($this->getProperty('file'));
        try {
            if ($data = $this->source->getMetadata($file)) {
                $base = $this->source->getBasePath();
                $target = explode(DIRECTORY_SEPARATOR, trim($data['path'], DIRECTORY_SEPARATOR));
                array_pop($target);
                $target = implode(DIRECTORY_SEPARATOR, $target);

                /** @noinspection PhpParamsInspection */
                if ($archive = new \xPDO\Compression\xPDOZip($this->modx, $base . $data['path'])) {
                    if (!$archive->unpack($this->source->postfixSlash($base . $target))) {
                        return $this->failure($this->modx->lexicon('file_err_unzip'));
                    }
                }
            } else {
                return $this->failure($this->modx->lexicon('file_err_open') . $this->getProperty('file'));
            }
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }

        return $this->success();
    }
}
