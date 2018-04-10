<?php

class modDashboardUserCreateProcessor extends modObjectCreateProcessor
{
    public $classKey = 'modDashboardWidgetPlacement';
    public $languageTopics = ['dashboards'];
    public $permission = '';
    /** @var modDashboardWidgetPlacement $object */
    public $object;


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->object->fromArray([
            'user' => (int)$this->modx->user->get('id'),
            'dashboard' => (int)$this->getProperty('dashboard'),
            'widget' => (int)$this->getProperty('widget'),
        ], '', true, true);

        return parent::beforeSet();
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        /** @var modDashboardWidget $widget */
        if ($widget = $this->object->getOne('Widget')) {
            if ($permission = $widget->get('permission')) {
                if (!$this->modx->hasPermission($permission)) {
                    return $this->modx->lexicon('access_denied');
                }
            }
        }
        $this->object->set('rank', $this->modx->getCount($this->classKey, [
            'user' => $this->object->get('user'),
            'dashboard' => $this->object->get('dashboard'),
        ]));

        return parent::beforeSave();
    }


    /**
     * @return bool
     */
    public function afterSave()
    {
        /** @var modDashboard $dashboard */
        if ($dashboard = $this->object->getOne('Dashboard')) {
            $dashboard->sortWidgets($this->modx->user->get('id'), true);
        }

        return parent::afterSave();
    }


    /**
     * Log event
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('user_widget_add', 'modDashboardWidget', $this->object->get('widget'));
    }
}

return 'modDashboardUserCreateProcessor';