<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__).'/remove.class.php');
/**
 * Removes a chunk.
 *
 * @param integer $id The ID of the chunk
 *
 * @package modx
 * @subpackage processors.element.chunk
 */
class modChunkRemoveProcessor extends modElementRemoveProcessor {
    public $classKey = 'modChunk';
    public $languageTopics = array('chunk');
    public $permission = 'delete_chunk';
    public $objectType = 'chunk';
    public $beforeRemoveEvent = 'OnBeforeChunkFormDelete';
    public $afterRemoveEvent = 'OnChunkFormDelete';

    public $staticFile = '';
    public $staticFilePath = '';

    public function beforeRemove() {
        if ($this->object->get('static_file')) {
            $source = $this->modx->getObject('sources.modFileMediaSource', array('id' => $this->object->get('source')));
            if ($source && $source->get('is_stream')) {
                $source->initialize();
                $this->staticFile = $this->object->get('static_file');
                $this->staticFilePath = $source->getBasePath() . $this->object->get('static_file');
            }
        }

        return true;
    }

    public function afterRemove() {
        $this->cleanupStaticFiles();

        return true;
    }
}
return 'modChunkRemoveProcessor';
