<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Forms\Set;

use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modX;

/**
 * Remove multiple FC sets
 * @package MODX\Revolution\Processors\Security\Forms\Set
 */
class RemoveMultiple extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('customize_forms');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['formcustomization'];
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $sets = $this->getProperty('sets');
        if (empty($sets)) {
            return $this->failure($this->modx->lexicon('set_err_ns'));
        }
        $setIds = explode(',', $sets);

        foreach ($setIds as $setId) {
            /** @var modFormCustomizationSet $set */
            $set = $this->modx->getObject(modFormCustomizationSet::class, $setId);
            if ($set) {
                if ($set->remove() === false) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('set_err_remove'));
                }
            }
        }
        return $this->success();
    }
}
