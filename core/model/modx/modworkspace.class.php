<?php
/**
 * @package modx
 */
/**
 * Workspaces are isolated packaging environments. They are currently not used in MODX.
 *
 * @property string $name The name of the Workspace
 * @property string $path The absolute path of the Workspace
 * @property timestamp $created The time this Workspace was created on
 * @property boolean $active Whether or not this Workspace is active
 * @property array $attributes An array of attributes for this Workspace
 *
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