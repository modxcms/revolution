<?php

class modDashboardUserWidgetSortProcessor extends modObjectUpdateProcessor
{
    public $classKey = 'modDashboardWidgetPlacement';
    public $languageTopics = ['dashboards'];
    public $permission = '';
    /** @var modDashboardWidgetPlacement $object */
    public $object;


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
     * @return array|mixed|string
     */
    public function process()
    {
        $from = $this->getProperty('from');
        $to = $this->getProperty('to');

        $where = [
            'user' => $this->object->get('user'),
            'dashboard' => $this->object->get('dashboard'),
        ];

        if ($from < $to) {
            $where['rank:<='] = $to;
            if ($from != 0) {
                $where[] = ["`{$this->classKey}`.`rank` > {$from} AND `{$this->classKey}`.`rank` > 0"];
            } else {
                $where['rank:>'] = $from;
            }

            $widgets = $this->modx->getIterator($this->classKey, $where);
            foreach ($widgets as $widget) {
                $widget->set('rank', $widget->get('rank') - 1);
                $widget->save();
            }
        } else {
            $where = array_merge($where, [
                'rank:>=' => $to,
                'rank:<' => $from,
            ]);
            $widgets = $this->modx->getIterator($this->classKey, $where);
            foreach ($widgets as $widget) {
                $widget->set('rank', $widget->get('rank') + 1);
                $widget->save();
            }
        }

        $this->object->set('rank', $to);
        $this->object->save();

        /** @var modDashboard $dashboard */
        if ($dashboard = $this->object->getOne('Dashboard')) {
            $dashboard->sortWidgets($this->object->get('user'));
        }

        return $this->success();
    }


    /**
     * Log event
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('user_widget_sort', 'modDashboardWidget', $this->object->get('widget'));
    }
}

return 'modDashboardUserWidgetSortProcessor';