<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Source\Type;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Sources\modMediaSource;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of media source types
 * @package MODX\Revolution\Processors\Source\Type
 */
class GetList extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('sources');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['source'];
    }

    /**
     * @return mixed|string
     */
    public function process()
    {
        $descendants = $this->modx->getDescendants(modMediaSource::class);

        $list = [];
        foreach ($descendants as $descendant) {
            /** @var xPDOObject|modMediaSource $obj */
            $obj = $this->modx->newObject($descendant);
            if (!$obj) {
                continue;
            }

            $list[] = [
                'class' => $descendant,
                'name' => $obj->getTypeName(),
                'description' => $obj->getTypeDescription(),
            ];
        }

        return $this->outputArray($list);
    }
}
