<?php

namespace MODX\Processors\System\Dashboard\Widget;

use MODX\Processors\modObjectCreateProcessor;

/**
 * Creates a new Dashboard Widget
 *
 * @package modx
 * @subpackage processors.system.dashboard.widget
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modDashboardWidget';
    public $languageTopics = ['dashboards'];
    public $permission = 'dashboards';
    public $objectType = 'widget';


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