<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Browser\Directory;


use MODX\Revolution\Processors\Browser\Browser;

/**
 * Sort a directory.
 *
 * @package MODX\Revolution\Processors\Browser\Directory
 */
class Sort extends Browser
{
    public $permission = 'directory_update';
    public $policy = 'save';
    public $languageTopics = ['file', 'source'];


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $from = $this->sanitize($this->getProperty('from'));
        if (!strlen($from)) {
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
