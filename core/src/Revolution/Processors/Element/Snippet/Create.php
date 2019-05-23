<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Snippet;


use MODX\Revolution\modSnippet;

/**
 * Create a snippet.
 *
 * @property string  $name        The name of the element
 * @property string  $snippet     The code of the snippet.
 * @property string  $description (optional) A brief description.
 * @property integer $category    (optional) The category to assign to. Defaults to no
 * category.
 * @property boolean $locked      (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @property string  $propdata    (optional) A JSON object of properties
 *
 * @package MODX\Revolution\Processors\Element\Snippet
 */
class Create extends \MODX\Revolution\Processors\Element\Create
{
    public $classKey = modSnippet::class;
    public $languageTopics = ['snippet', 'category', 'element'];
    public $permission = 'new_snippet';
    public $objectType = 'snippet';
    public $beforeSaveEvent = 'OnBeforeSnipFormSave';
    public $afterSaveEvent = 'OnSnipFormSave';

    public function beforeSave()
    {
        $isStatic = intval($this->getProperty('static', 0));

        if ($isStatic == 1) {
            $staticFile = $this->getProperty('static_file');

            if (empty($staticFile)) {
                $this->addFieldError('static_file', $this->modx->lexicon('static_file_ns'));
            }
        }

        return parent::beforeSave();
    }
}
