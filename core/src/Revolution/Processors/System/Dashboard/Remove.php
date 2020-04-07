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
use MODX\Revolution\Processors\Model\RemoveProcessor;

/**
 * Removes a Dashboard
 * @param integer $id The ID of the dashboard
 * @package MODX\Revolution\Processors\System\Dashboard
 */
class Remove extends RemoveProcessor
{
    public $classKey = modDashboard::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'dashboard';

    /**
     * @return array|bool|string
     */
    public function beforeRemove()
    {
        if ($this->object->get('id') === 1 || $this->object->get('name') === $this->modx->lexicon('default')) {
            return $this->failure($this->modx->lexicon('dashboard_err_remove_default'));
        }
        return parent::beforeRemove();
    }
}
