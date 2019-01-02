<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Duplicates a dashboard.
 *
 * @param integer $id The dashboard to duplicate
 * @param string $name The name of the new chunk.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
class modDashboardDuplicateProcessor extends modObjectDuplicateProcessor {
    public $classKey = 'modDashboard';
    public $languageTopics = array('dashboards');
    public $permission = 'dashboards';
    public $objectType = 'dashboard';

    public function beforeSave() {
        $this->duplicatePlacements();
        return parent::beforeSave();
    }

    public function duplicatePlacements() {
        $oldPlacements = $this->modx->getCollection('modDashboardWidgetPlacement',array(
            'dashboard' => $this->object->get('id'),
        ));
        $placements = array();
        /** @var modDashboardWidgetPlacement $placement */
        foreach ($oldPlacements as $placement) {
            /** @var modDashboardWidgetPlacement $placementCopy */
            $placementCopy = $this->modx->newObject('modDashboardWidgetPlacement');
            $placementCopy->fromArray(array(
                'widget' => $placement->get('widget'),
                'rank' => $placement->get('rank'),
            ),'',true,true);
            $placements[] = $placementCopy;
        }
        $this->newObject->addMany($placements);
    }
}
return 'modDashboardDuplicateProcessor';
