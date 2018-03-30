<?php

class modDashboardUserWidgetRemove extends modObjectRemoveProcessor
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
    public function afterRemove()
    {
        /** @var modDashboard $dashboard */
        if ($dashboard = $this->object->getOne('Dashboard')) {
            $dashboard->sortWidgets($this->modx->user->get('id'), true);
        }

        return parent::afterRemove();
    }


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        if ($this->removeObject() == false) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_remove'));
        }
        $this->afterRemove();
        $this->fireAfterRemoveEvent();
        $this->logManagerAction();

        $new_widgets = 0;
        $this->modx->error->reset();
        /** @var modProcessorResponse $res */
        $res = $this->modx->runProcessor('system/dashboard/user/getlist', [
            'dashboard' => $this->object->get('dashboard'),
            'combo' => true
        ]);
        if (!$res->isError()) {
            $tmp = $res->getResponse();
            if (is_string($tmp)) {
                $tmp = json_decode($tmp, true);
            }
            if (isset($tmp['total'])) {
                $new_widgets = $tmp['total'];
            }
        }

        return $this->success('', [
            'new_widgets' => $new_widgets,
        ]);
    }

    /**
     * Log event
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('user_widget_remove', 'modDashboardWidget', $this->object->get('widget'));
    }
}

return 'modDashboardUserWidgetRemove';