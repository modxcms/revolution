<?php
/**
 * Gets a list of recently edited resources by a user
 *
 * @param integer $user The user to grab from
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.user
 */

class modUserGetRecentlyEditedResourcesProcessor extends modObjectGetListProcessor {
    public $objectType = 'user';
    public $classKey = 'modResource';
    public $permission = 'view_document';
    public $languageTopics = array('resource', 'user');
    public $defaultSortField = 'editedon';
    public $defaultSortDirection = 'DESC';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
        ));

        $user = (int)$this->getProperty('user', 0);
        if (!$user) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }
        /** @var modUser $user */
        $this->object = $this->modx->getObject('modUser', $user);
        if (!$this->object) {
            return $this->modx->lexicon($this->objectType.'_err_nf');
        }

        return parent::initialize();
    }

    /**
     * Filter resources by user
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->select(array(
            'id','pagetitle','description','published','deleted', 'context_key'
        ));
        $c->where(array(
            array(
                'editedby' => $this->object->get('id'),
            ),
            array(
                'OR:editedby:=' => 0,
                'AND:createdby:=' => $this->object->get('id')
            ),
        ));
        return $c;
    }

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        if (!$object->checkPolicy('view')) return array();

        $resourceArray = $object->get(array('id','pagetitle','description','published','deleted', 'context_key'));
        $resourceArray['pagetitle'] = htmlspecialchars($resourceArray['pagetitle'], ENT_QUOTES, $this->modx->getOption('modx_charset', null, 'UTF-8'));
        $resourceArray['menu'] = array();
        $resourceArray['menu'][] = array(
            'text' => $this->modx->lexicon('resource_view'),
            'params' => array(
                'a' => 'resource/data',
                'id' => $object->get('id'),
            ),
        );
        if ($this->modx->hasPermission('edit_document')) {
            $resourceArray['menu'][] = array(
                'text' => $this->modx->lexicon('resource_edit'),
                'params' => array(
                    'a' => 'resource/update',
                    'id' => $object->get('id'),
                ),
            );
        }
        $resourceArray['menu'][] = '-';
        $resourceArray['menu'][] = array(
            'text' => $this->modx->lexicon('resource_preview'),
            'handler' => 'this.preview',
        );

        return $resourceArray;
    }
}

return 'modUserGetRecentlyEditedResourcesProcessor';
