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
 * Delete a snippet.
 *
 * @param integer $id The ID of the snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
class modSnippetRemoveProcessor extends modElementRemoveProcessor {
    public $classKey = 'modSnippet';
    public $languageTopics = array('snippet');
    public $permission = 'delete_snippet';
    public $objectType = 'snippet';
    public $beforeRemoveEvent = 'OnBeforeSnipFormDelete';
    public $afterRemoveEvent = 'OnSnipFormDelete';

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
return 'modSnippetRemoveProcessor';
