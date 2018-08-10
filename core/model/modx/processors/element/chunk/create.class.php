<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__).'/create.class.php');
/**
 * Creates a chunk.
 *
 * @param string $name The name of the chunk.
 * @param string $description (optional) The description of the chunk.
 * @param integer $category The category the chunk is assigned to.
 * @param string $snippet The code of the chunk.
 * @param boolean $locked Whether or not the chunk can only be accessed by
 * administrators.
 * @param json $propdata A json array of properties to store.
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class modChunkCreateProcessor extends modElementCreateProcessor {
    public $classKey = 'modChunk';
    public $languageTopics = array('chunk','category','element');
    public $permission = 'new_chunk';
    public $objectType = 'chunk';
    public $beforeSaveEvent = 'OnBeforeChunkFormSave';
    public $afterSaveEvent = 'OnChunkFormSave';

    public function beforeSave() {
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
return 'modChunkCreateProcessor';
