<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Dashboard\Widget;

use MODX\Revolution\modDashboardWidget;
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Creates a new Dashboard Widget
 * @package MODX\Revolution\Processors\System\Dashboard\Widget
 */
class Create extends CreateProcessor
{
    public $classKey = modDashboardWidget::class;
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'widget';

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name', $this->modx->lexicon('widget_err_ns_name'));
        } else {
            if ($this->doesAlreadyExist(['name' => $name])) {
                $this->addFieldError('name', $this->modx->lexicon('widget_err_ae_name', [
                    'name' => $name,
                ]));
            }
        }

        return parent::beforeSave();
    }
}
