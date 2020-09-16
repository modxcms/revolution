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
 * Gets all files in a directory
 *
 * @property string  $dir         The directory to browse
 *
 * @package MODX\Revolution\Processors\Browser\Directory
 */
class GetFiles extends Browser
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
