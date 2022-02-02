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
 * Get a list of directories and files, sorting them first by folder/file and
 * then alphanumerically.
 *
 * @property string  $id          The path to grab a list from
 * @property boolean $hideFiles   (optional) If true, will not display files.
 * Defaults to false.
 *
 * @package MODX\Revolution\Processors\Browser\Directory
 */
class GetList extends Browser
{
    public $permission = 'directory_list';
    public $policy = 'list';
    public $languageTopics = ['file', 'source'];


    /**
     * @return array|mixed|string
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
            : json_encode($list, JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
