<?php
/**
 * Gets a list of user groups
 *
 * @param boolean $combo (optional) If true, will append a (anonymous) row
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.group
 */
class modUserGroupGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
    }
    public function getLanguageTopics() {
        return array('user','messages');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'sort' => 'name',
            'dir' => 'ASC',
            'exclude' => '',
        ));
        return true;
    }

    public function process() {
        $data = $this->getData();
        $list = array();
        
        if (!empty($scriptProperties['addAll'])) {
            $list[] = array(
                'id' => '',
                'name' => '('.$this->modx->lexicon('all').')',
                'description' => '',
                'parent' => 0,
            );
        }
        if (!empty($scriptProperties['addNone'])) {
            $list[] = array(
                'id' => 0,
                'name' => $this->modx->lexicon('none'),
                'description' => '',
                'parent' => 0,
            );
        }
        if (!empty($scriptProperties['combo'])) {
            $list[] = array(
                'id' => '',
                'name' => ' ('.$this->modx->lexicon('anonymous').') ',
                'description' => '',
                'parent' => 0,
            );
        }
        /** @var modUserGroup $userGroup */
        foreach ($data['results'] as $userGroup) {
            $list[] = $userGroup->toArray();
        }
        return $this->outputArray($list,$data['total']);
    }

    /**
     * @return array
     */
    public function getData() {
        $c = $this->modx->newQuery('modUserGroup');

        $exclude = $this->getProperty('exclude','');
        if (!empty($exclude)) {
            $c->where(array(
                'id:NOT IN' => is_array($exclude) ? $exclude : explode(',',$exclude),
            ));
        }

        $data['total'] = $this->modx->getCount('modUserGroup',$c);

        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($this->getProperty('limit') > 0) {
            $c->limit($this->getProperty('limit'),$this->getProperty('start'));
        }
        $data['results'] = $this->modx->getCollection('modUserGroup',$c);

        return $data;
    }
}
return 'modUserGroupGetListProcessor';
