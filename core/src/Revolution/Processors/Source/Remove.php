<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Source;

use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Removes a Media Source
 * @param integer $id The ID of the source
 * @package MODX\Revolution\Processors\Source
 */
class Remove extends RemoveProcessor
{
    public $classKey = modMediaSource::class;
    public $languageTopics = ['source'];
    public $permission = 'source_delete';
    public $objectType = 'source';
    public $beforeRemoveEvent = 'OnMediaSourceBeforeFormDelete';
    public $afterRemoveEvent = 'OnMediaSourceFormDelete';

    /**
     * @return bool|string|null
     */
    public function beforeRemove()
    {
        if ($this->object->get('id') === 1) {
            return $this->modx->lexicon('source_err_remove_default');
        }
        return true;
    }
}
