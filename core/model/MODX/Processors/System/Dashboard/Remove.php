<?php

namespace MODX\Processors\System\Dashboard;

use MODX\Processors\modObjectRemoveProcessor;

/**
 * Removes a Dashboard
 *
 * @param integer $id The ID of the dashboard
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
class Remove extends modObjectRemoveProcessor
{
    public $classKey = 'modDashboard';
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'dashboard';


    public function beforeRemove()
    {
        if ($this->object->get('id') == 1 || $this->object->get('name') == $this->modx->lexicon('default')) {
            return $this->failure($this->modx->lexicon('dashboard_err_remove_default'));
        }

        return parent::beforeRemove();
    }
}