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
 * Removes a Dashboard
 *
 * @param integer $id The ID of the dashboard
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
class modDashboardRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modDashboard';
    public $languageTopics = array('dashboards');
    public $permission = 'dashboards';
    public $objectType = 'dashboard';

    public function beforeRemove() {
        if ($this->object->get('id') == 1 || $this->object->get('name') == $this->modx->lexicon('default')) {
            return $this->failure($this->modx->lexicon('dashboard_err_remove_default'));
        }
        return parent::beforeRemove();
    }
}
return 'modDashboardRemoveProcessor';
