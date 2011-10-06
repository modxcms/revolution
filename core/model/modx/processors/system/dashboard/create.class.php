<?php
/**
 * Creates a Dashboard
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
class modDashboardCreateProcessor extends modProcessor {
    /** @var modDashboard $dashboard */
    public $dashboard;
    
    public function checkPermissions() {
        return $this->modx->hasPermission('dashboards');
    }
    public function getLanguageTopics() {
        return array('dashboards');
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function initialize() {
        $this->dashboard = $this->modx->newObject('modDashboard');
        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        $fields = $this->getProperties();

        if (!$this->validate($fields)) {
            $this->failure();
        }

        $this->dashboard->fromArray($fields);
        if ($this->dashboard->save() == false) {
            return $this->modx->error->failure($this->modx->lexicon('dashboard_err_save'));
        }

        if (array_key_exists('widgets',$fields)) {
            $this->assignWidgets($fields['widgets']);
        }

        return $this->modx->error->success('',$this->dashboard);
    }

    /**
     * Validate against the properties sent
     * @param array $fields
     * @return bool
     */
    public function validate(array $fields) {
        /* validate name field */
        if (empty($fields['name'])) {
            $this->addFieldError('name',$this->modx->lexicon('dashboard_err_ns_name'));
            
        } else if ($this->alreadyExists($fields['name'])) {
            $this->addFieldError('name',$this->modx->lexicon('dashboard_err_ae_name',array(
                'name' => $fields['name'],
            )));
        }

        return !$this->hasErrors();
    }

    /**
     * See if a Dashboard already exists with this name
     *
     * @param string $name
     * @return bool
     */
    public function alreadyExists($name) {
        return $this->modx->getCount('modDashboard',array('name' => $name)) > 0;
    }

    /**
     * Assign widgets to this dashboard
     * 
     * @param array $widgets
     * @return array An array of placement objects
     */
    public function assignWidgets(array $widgets) {
        $placements = array();
        
        /** @var array $widgets */
        $widgets = is_array($widgets) ? $widgets : $this->modx->fromJSON($widgets);

        /** @var array $widget */
        foreach ($widgets as $widget) {
            /** @var modDashboardWidgetPlacement $placement */
            $placement = $this->modx->newObject('modDashboardWidgetPlacement');
            $placement->set('dashboard',$this->dashboard->get('id'));
            $placement->set('widget',$widget['widget']);
            $placement->set('rank',$widget['rank']);
            $placement->save();
            $placements[] = $placement;
        }
        return $placements;
    }

    /**
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction('dashboard_create','modDashboard',$this->dashboard->get('id'));
    }
}
return 'modDashboardCreateProcessor';