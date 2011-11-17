<?php
/**
 * Get a list of messages
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to date_sent.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.message
 */
class modUserMessageGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modUserMessage';
    public $languageTopics = array('messages','user');
    public $permission = 'messages';
    public $defaultSortField = 'date_sent';

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'search' => '',
        ));
        return $initialized;
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->innerJoin('modUser','Sender');
        $c->where(array(
            'recipient' => $this->modx->user->get('id'),
        ));
        $search = $this->getProperty('search','');
        if (!empty($search)) {
            $c->andCondition(array(
                'subject:LIKE' => '%'.$search.'%',
                'OR:message:LIKE' => '%'.$search.'%',
            ),null,2);
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns('modUserMessage','modUserMessage'));
        $c->select(array(
            'sender_username' => 'Sender.username',
        ));
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['sender_name'] = $object->get('sender_username');
        $objectArray['read'] = $object->get('read') ? true : false;
        return $objectArray;
    }
}
return 'modUserMessageGetListProcessor';