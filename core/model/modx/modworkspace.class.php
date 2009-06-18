<?php
/**
 * @package modx
 */
class modWorkspace extends xPDOSimpleObject {
    function modWorkspace(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Overrides xPDOObject::save to set the createdon date.
     *
     * {@inheritdoc}
     */
    function save($cacheFlag= null) {
        if ($this->_new && !$this->get('created')) {
            $this->set('created', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }
}