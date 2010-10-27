<?php
/**
 * Defines criteria a principal must satisfy in order to access an object.
 *
 * @package modx
 */
class modAccessPolicy extends xPDOSimpleObject {
    public function getPermissions() {
        $template = $this->getOne('Template');
        if (empty($template)) return array();

        /* get permissions for policy */
        $c = $this->xpdo->newQuery('modAccessPermission');
        $c->sortby('name','ASC');
        $permissions = $template->getMany('Permissions',$c);

        $data = $this->get('data');
        $lexicon = $template->get('lexicon');
        $list = array();
        foreach ($permissions as $permission) {
            $desc = $permission->get('description');
            if (!empty($lexicon) && $this->xpdo->lexicon) {
                if (strpos($lexicon,':') !== false) {
                    $this->xpdo->lexicon->load($lexicon);
                } else {
                    $this->xpdo->lexicon->load('core:'.$lexicon);
                }
                $desc = $this->xpdo->lexicon($desc);
            }
            $active = array_key_exists($permission->get('name'),$data) && $data[$permission->get('name')] ? 1 : 0;
            $list[] = array(
                $permission->get('name'),
                $permission->get('description'),
                $desc,
                $permission->get('value'),
                $active,
            );
        }
        return $list;
    }
}