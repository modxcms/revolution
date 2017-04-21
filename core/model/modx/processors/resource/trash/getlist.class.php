<?php
/**
 * Gets a list of resources.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @return array An array of modResources
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceTrashGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $defaultSortField = 'pagetitle';
    public $permission = 'view';

    /*
    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'showNone' => false,
            'query' => '',
            'streamsOnly' => false,
        ));
        return $initialized;
    }
    */

    /**
     * @return string
     */
    public function getSortClassKey() {
        return 'modResource';
    }

    /**
     * @param array $list
     *
     * @return array
     */
    public function beforeIteration(array $list) {
        /*if ($this->getProperty('showNone')) {
            $list[] = array(
                'id' => 0,
                'name' => '('.$this->modx->lexicon('none').')',
                'description' => '',
            );
        }*/
        return $list;
    }

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array('modResource.pagetitle:LIKE' => '%'.$query.'%'));
            $c->orCondition(array('modResource.longtitle:LIKE' => '%'.$query.'%'));
        }
        $c->where(array(
            'modResource.deleted' => true,
        ));
        //$c->prepare();
        //$this->modx->log(1,"Query: ".$c->toSQL());
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $charset = $this->modx->getOption('modx_charset',null,'UTF-8');
        $objectArray = $object->toArray();
        $objectArray['pagetitle'] = htmlentities($objectArray['pagetitle'],ENT_COMPAT,$charset);

        $canEdit = $this->modx->hasPermission('source_edit');
        $canSave = $this->modx->hasPermission('source_save');
        $canRemove = $this->modx->hasPermission('source_delete');

        $objectArray = $object->toArray();
        $objectArray['iconCls'] = $this->modx->getOption('mgr_source_icon', null, 'icon-folder-open-o');

        $cls = array();
        $cls[] = 'restore';
        $cls[] = 'purge';


        /*if ($object->checkPolicy('save') && $canSave && $canEdit) $cls[] = 'pupdate';
        if ($object->checkPolicy('remove') && $canRemove) $cls[] = 'premove';
        if ($object->checkPolicy('copy') && $canSave) $cls[] = 'pduplicate';
*/
        $objectArray['cls'] = implode(' ',$cls);
        return $objectArray;


    }
}
return 'modResourceTrashGetListProcessor';

