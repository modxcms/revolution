<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;

/**
 * A relation between Template Variables and Resource Groups. Only user groups with the specified Resource Groups, if
 * any are set, will be able to edit the TV.
 *
 * @property int $tmplvarid     The ID of the related TV
 * @property int $documentgroup The ID of the related Resource Group
 * @property int $createdon     The UNIX time of when this TV access record was created
 * @property int $editedon      The UNIX time, if set, of when this TV access record was last edited
 *
 * @package MODX\Revolution
 */
class modTemplateVarResourceGroup extends xPDOSimpleObject
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
