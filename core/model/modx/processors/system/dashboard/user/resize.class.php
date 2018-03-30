<?php

class modDashboardUserWidgetReSizeProcessor extends modObjectUpdateProcessor
{
    public $classKey = 'modDashboardWidgetPlacement';
    public $languageTopics = ['dashboards'];
    public $permission = '';


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        $primaryKey = [
            'user' => (int)$this->modx->user->get('id'),
            'dashboard' => (int)$this->getProperty('dashboard'),
            'widget' => (int)$this->getProperty('widget'),
        ];

        if (!$this->modx->getCount('modDashboard', ['id' => $primaryKey['dashboard'], 'customizable' => true])) {
            return $this->modx->lexicon('access_denied');
        } elseif (!$this->object = $this->modx->getObject($this->classKey, $primaryKey)) {
            return $this->modx->lexicon($this->objectType . '_err_nfs');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->setProperties([
            'size' => $this->getProperty('size'),
        ]);

        return parent::beforeSet();
    }

    /**
     * Log event
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('user_widget_resize', 'modDashboardWidget', $this->object->get('widget'));
    }
}

return 'modDashboardUserWidgetReSizeProcessor';