<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Dashboard;

use MODX\Revolution\modDashboard;
use MODX\Revolution\modDashboardWidgetPlacement;
use MODX\Revolution\Processors\Model\DuplicateProcessor;

/**
 * Duplicates a dashboard.
 * @param integer $id The dashboard to duplicate
 * @param string $name The name of the new chunk.
 * @package MODX\Revolution\Processors\System\Dashboard
 */
class Duplicate extends DuplicateProcessor
{
    public $classKey = modDashboard::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'dashboard';

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->duplicatePlacements();
        return parent::beforeSave();
    }

    /**
     *
     */
    public function duplicatePlacements()
    {
        $oldPlacements = $this->modx->getCollection(modDashboardWidgetPlacement::class, [
            'dashboard' => $this->object->get('id'),
        ]);
        $placements = [];
        /** @var modDashboardWidgetPlacement $placement */
        foreach ($oldPlacements as $placement) {
            /** @var modDashboardWidgetPlacement $placementCopy */
            $placementCopy = $this->modx->newObject(modDashboardWidgetPlacement::class);
            $placementCopy->fromArray([
                'widget' => $placement->get('widget'),
                'rank' => $placement->get('rank'),
            ], '', true, true);
            $placements[] = $placementCopy;
        }
        $this->newObject->addMany($placements);
    }
}
