<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\ContentType;

use MODX\Revolution\modContentType;
use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\modResource;

/**
 * Removes a content type
 * @param integer $id The ID of the content type
 * @package MODX\Revolution\Processors\System\ContentType
 */
class Remove extends RemoveProcessor
{
    public $classKey = modContentType::class;
    public $languageTopics = ['content_type'];
    public $permission = 'content_types';
    public $objectType = 'content_type';

    /**
     * @return bool|string|null
     */
    public function beforeRemove()
    {
        if ($this->isInUse()) {
            return $this->modx->lexicon('content_type_err_in_use');
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isInUse()
    {
        return $this->modx->getCount(modResource::class, ['content_type' => $this->object->get('id')]) > 0;
    }
}
