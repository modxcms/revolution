<?php

use xPDO\Om\xPDOQuery;

class modDashboardUserWidgetGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modDashboardWidget';
    public $languageTopics = ['dashboards'];
    public $permission = '';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $q = $this->modx->newQuery('modDashboardWidgetPlacement', [
            'dashboard' => $this->getProperty('dashboard'),
            'user' => $this->modx->user->get('id'),
        ]);
        $q->select('widget');
        if ($q->prepare() && $q->stmt->execute()) {
            if ($exists = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
                $c->where(['id:NOT IN' => $exists]);
            }
        }

        return $c;
    }


    /**
     * @param array $data
     *
     * @return array
     */
    public function iterate(array $data)
    {
        $list = [];
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            if ($object->get('permission') && !$this->modx->hasPermission($object->get('permission'))) {
                $data['total'] = $data['total'] - 1;
                continue;
            }
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);

        return $list;
    }

}

return 'modDashboardUserWidgetGetListProcessor';