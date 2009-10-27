<?php
/**
 * @package modx
 */
class modWorkspace extends xPDOSimpleObject {

    /**
     * Overrides xPDOObject::save to set the createdon date.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag= null) {
        if ($this->_new && !$this->get('created')) {
            $this->set('created', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }
}