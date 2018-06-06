<?php

namespace MODX\Processors\System\Dashboard;

use MODX\modDashboardWidgetPlacement;
use MODX\Processors\modObjectDuplicateProcessor;

/**
 * Duplicates a dashboard.
 *
 * @param integer $id The dashboard to duplicate
 * @param string $name The name of the new chunk.
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
class Duplicate extends modObjectDuplicateProcessor
{
    public $classKey = 'modDashboard';
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'dashboard';


    public function beforeSave()
    {
        $this->duplicatePlacements();

        return parent::beforeSave();
    }


    public function duplicatePlacements()
    {
        $oldPlacements = $this->modx->getCollection('modDashboardWidgetPlacement', [
            'dashboard' => $this->object->get('id'),
        ]);
        $placements = [];
        /** @var modDashboardWidgetPlacement $placement */
        foreach ($oldPlacements as $placement) {
            /** @var modDashboardWidgetPlacement $placementCopy */
            $placementCopy = $this->modx->newObject('modDashboardWidgetPlacement');
            $placementCopy->fromArray([
                'widget' => $placement->get('widget'),
                'rank' => $placement->get('rank'),
            ], '', true, true);
            $placements[] = $placementCopy;
        }
        $this->newObject->addMany($placements);
    }
}