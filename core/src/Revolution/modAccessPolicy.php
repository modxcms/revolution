<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

/**
 * Defines criteria a principal must satisfy in order to access an object.
 *
 * @property string            $name        The name of this Policy
 * @property string            $description The description of this Policy.
 * @property int               $parent      The parent Policy of this Policy. Not currently used.
 * @property int               $template    The Access Policy Template this Policy belongs to
 * @property string            $class       Deprecated
 * @property array             $data        A JSON object that contains all Permissions loaded in this Policy
 * @property string            $lexicon     Optional. The lexicon to load to provide the translated names/descriptions for the
 * Permissions included
 *
 * @property modAccessPolicy[] $Children
 *
 * @property modX|xPDO         $xpdo
 *
 * @package MODX\Revolution
 */
class modAccessPolicy extends xPDOSimpleObject
{
    /**
     * Get the permissions for this access policy, in array format.
     *
     * @return array An array of access permissions for this Policy.
     */
    public function getPermissions()
    {
        $template = $this->getOne('Template');
        if (empty($template)) {
            return [];
        }

        /* get permissions for policy */
        $c = $this->xpdo->newQuery(modAccessPermission::class);
        $c->sortby('name', 'ASC');
        $permissions = $template->getMany('Permissions', $c);

        $data = $this->get('data');
        $lexicon = $template->get('lexicon');
        $list = [];
        /** @var modAccessPermission $permission */
        foreach ($permissions as $permission) {
            $desc = $permission->get('description');
            if (!empty($lexicon) && $this->xpdo->lexicon) {
                if (strpos($lexicon, ':') !== false) {
                    $this->xpdo->lexicon->load($lexicon);
                } else {
                    $this->xpdo->lexicon->load('core:' . $lexicon);
                }
                $desc = $this->xpdo->lexicon($desc);
            }
            $active = array_key_exists($permission->get('name'), $data) && $data[$permission->get('name')] ? 1 : 0;
            $list[] = [
                $permission->get('name'),
                $permission->get('description'),
                $desc,
                $permission->get('value'),
                $active,
            ];
        }

        return $list;
    }
}
