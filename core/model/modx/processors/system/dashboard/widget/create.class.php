<?php
/**
 * Creates a new Dashboard Widget
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard.widget
 */
class modDashboardWidgetCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modDashboardWidget';
    public $languageTopics = array('dashboards');
    public $permission = 'dashboards';
    public $objectType = 'widget';
    
    public function beforeSave() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('widget_err_ns_name'));
        } else {
            if ($this->doesAlreadyExist(array('name' => $name))) {
                $this->addFieldError('name',$this->modx->lexicon('widget_err_ae_name',array(
                    'name' => $name,
                )));
            }
        }

        return parent::beforeSave();
    }
}
return 'modDashboardWidgetCreateProcessor';