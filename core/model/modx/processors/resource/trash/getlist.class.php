<?php

/**
 * Gets a list of resources.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 *                       to 10.
 * @param string  $sort  (optional) The column to sort by. Defaults to name.
 * @param string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @return array An array of modResources
 * @package    modx
 * @subpackage processors.resource
 */
class modResourceTrashGetListProcessor extends modObjectGetListProcessor {

    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $defaultSortField = 'pagetitle';
    public $permission = 'view';

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        $c->select([
            $this->modx->getSelectColumns('modResource', 'modResource', 'modResource_'),
            'modResource_deletedbyUser' => 'User.username',
            'modResource_context_name'  => 'Context.name',
        ]);
        $c->leftJoin('modUser', 'User', 'modResource.deletedby = User.id');
        $c->leftJoin('modContext', 'Context', 'modResource.context_key = Context.key');

        // TODO add only resources if we have the save permission here (on the context!!)
        // we need the following permissions:
        // undelete_document - to restore the document
        // delete_document - thats perhaps not necessary, because all documents are already deleted
        // but we need the purge_deleted permission - for every single file

        if (!empty($query)) {
            $c->where(array('modResource.pagetitle:LIKE' => '%' . $query . '%'));
            $c->orCondition(array('modResource.longtitle:LIKE' => '%' . $query . '%'));
        }
        $c->where(array(
            'modResource.deleted' => true,
        ));
        // $c->prepare();
        // $this->modx->log(1,"Query: ".$c->toSQL());
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        // quick exit if we don't have access to the context
        // this is a strange workaround: obviously we can access the resources even if we don't have access to the context! Check that
        // TODO check if that is the same for resource groups
        $context = $this->modx->getContext($object->get('context_key'));
        if (!$context) return [];

        $charset = $this->modx->getOption('modx_charset', null, 'UTF-8');
        $objectArray = $object->toArray();
        $objectArray['pagetitle'] = htmlentities($objectArray['pagetitle'], ENT_COMPAT, $charset);

        // to enable a better detection of the resource's location, we also construct the
        // parent-child path to the resource

        $parents = array();
        $parent = $objectArray['parent'];

        while ($parent!=0) {
            $parents[] = $this->modx->getObject('modResource', $parent);
            $parent = end($parents)->get('parent');
        }

        $parentPath = "";
        foreach ($parents as $parent) {
            $parentPath = $parent->get('pagetitle') . " (".$parent->get('id') . ") > " . $parentPath;
        }
        $objectArray['parentPath'] =  "[" . $objectArray['context_key'] . "] " . $parentPath;

        //  TODO implement permission checks for every resource and return only resources user is allowed to see

        // show the permissions for the context
        $canView = $this->modx->hasPermission('view');
        $canPurge = $this->modx->hasPermission('purge_deleted');
        $canUndelete = $this->modx->hasPermission('undelete_document');
        $canPublish = $this->modx->hasPermission('publish');
        $canSave = $this->modx->hasPermission('save');
        $canEdit = $this->modx->hasPermission('edit');
        $canList = $this->modx->hasPermission('list');
        $canLoad = $this->modx->hasPermission('load');

        $objectArray['iconCls'] = $this->modx->getOption('mgr_source_icon', null, 'icon-folder-open-o');

        $cls = array();
        $cls[] = 'restore';
        $cls[] = 'purge';
        $cls[] = 'undelete_document';

        $cls = array();
        if ($object->checkPolicy('purge_deleted') && $canSave && $canEdit && $canPurge) {
            $cls[] = 'trashpurge';
        }
        if ($object->checkPolicy('undelete_document') && $canSave && $canEdit) {
            $cls[] = 'trashundelete';
        }
        if ($object->checkPolicy('save') && $canSave && $canEdit) {
            $cls[] = 'trashsave';
        }
        if ($object->checkPolicy('edit') && $canSave && $canEdit) {
            $cls[] = 'trashedit';
        }
        $cls[] = 'trashrow';


        $debug = array('id'=>$object->get('id'), 'pagetitle'=>$object->get('pagetitle'));
        $this->modx->log(1,"array: ".print_r($debug,true));
        $this->modx->log(1,"Can list: ".$canList.", list:".$object->checkPolicy('list'));
        $this->modx->log(1,"Can load: ".$canLoad.", load: ".$object->checkPolicy('load'));
        $this->modx->log(1,"Can view: ".$canView.", view_doc:".$object->checkPolicy('view_document').", view: ".$object->checkPolicy('view'));
        $this->modx->log(1,"Can save: ".$canSave.", save_doc:".$object->checkPolicy('save_document').", save: ".$object->checkPolicy('save'));
        $this->modx->log(1,"Can edit: ".$canEdit.", edit_doco:".$object->checkPolicy('edit_document').", edit: ".$object->checkPolicy('edit'));
        $this->modx->log(1,"Can purge: ".$canPurge.", purge_deleted:".$object->checkPolicy('purge_deleted'));

        $this->modx->log(1,"context: ".$object->get('context_key'));

        $objectArray['cls'] = implode(' ', $cls);

        return $objectArray;


    }
}

return 'modResourceTrashGetListProcessor';

