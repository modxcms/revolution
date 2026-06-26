<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * Stores the value of a TV for a specific Resource
 *
 * @property int    $tmplvarid The ID of the related TV
 * @property int    $contentid The ID of the related Resource
 * @property string $value     The stored value of the TV for the Resource
 * @property int    $createdon The UNIX time of when this TV value was created
 * @property int    $editedon  The UNIX time, if set, of when this TV value was last edited
 *
 * @package MODX\Revolution
 */
class modTemplateVarResource extends xPDOSimpleObject
{
    /**
     * {@inheritDoc}
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            if (!$this->get('createdon')) {
                $this->set('createdon', time(), 'integer');
            }
        } else {
            $this->set('editedon', time(), 'integer');
        }

        return parent::save($cacheFlag);
    }
}
