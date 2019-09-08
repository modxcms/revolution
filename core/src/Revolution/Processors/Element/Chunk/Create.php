<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Chunk;


use MODX\Revolution\modChunk;

/**
 * Creates a chunk.
 *
 * @property string  $name        The name of the chunk.
 * @property string  $description (optional) The description of the chunk.
 * @property integer $category    The category the chunk is assigned to.
 * @property string  $snippet     The code of the chunk.
 * @property boolean $locked      Whether or not the chunk can only be accessed by
 * administrators.
 * @property string  $propdata    A JSON object of properties to store.
 *
 * @package MODX\Revolution\Processors\Element\Chunk
 */
class Create extends \MODX\Revolution\Processors\Element\Create
{
    public $classKey = modChunk::class;
    public $languageTopics = ['chunk', 'category', 'element'];
    public $permission = 'new_chunk';
    public $objectType = 'chunk';
    public $beforeSaveEvent = 'OnBeforeChunkFormSave';
    public $afterSaveEvent = 'OnChunkFormSave';

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
