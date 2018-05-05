<?php

/**
 * Get a list of messages
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to date_sent.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package modx
 * @subpackage processors.security.message
 */
class modUserMessageGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modUserMessage';
    public $languageTopics = array('messages', 'user');
    public $permission = 'messages';
    public $defaultSortField = 'date_sent';

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'search' => '',
            'type' => 'inbox'
        ));

        return $initialized;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin('modUser', 'Sender');
        $c->innerJoin('modUser', 'Recipient');
        $c->innerJoin('modUserProfile', 'SenderProfile', 'SenderProfile.id = modUserMessage.sender');
        $c->innerJoin('modUserProfile', 'RecipientProfile', 'RecipientProfile.id = modUserMessage.recipient');

        switch ($this->getProperty('type')) {
            case 'outbox':
                $where = array('sender' => $this->modx->user->get('id'));
                break;
            case 'inbox':
            default:
                $where = array('recipient' => $this->modx->user->get('id'));
                break;
        }

        $c->where($where);
        $search = $this->getProperty('search', '');
        if (!empty($search)) {
            $c->andCondition(array(
                'subject:LIKE' => '%' . $search . '%',
                'OR:message:LIKE' => '%' . $search . '%',
            ), null, 2);
        }

        return $c;
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns('modUserMessage', 'modUserMessage'));
        $c->select(array(
            'sender_username' => 'Sender.username',
            'sender_fullname' => 'SenderProfile.fullname',
            'recipient_username' => 'Recipient.username',
            'recipient_fullname' => 'RecipientProfile.fullname'
        ));
        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['sender_name'] = $object->get('sender_fullname') . " ({$object->get('sender_username')})";
        $objectArray['recipient_name'] = $object->get('recipient_fullname') . " ({$object->get('recipient_username')})";
        $objectArray['read'] = $object->get('read') ? true : false;
        return $objectArray;
    }
}

return 'modUserMessageGetListProcessor';
