<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;

/**
 * Represents a relationship between a template and a template variable. All TVs can be assigned to show on specified
 * Templates.
 *
 * @property int $tmplvarid  The ID of the related TV
 * @property int $templateid The ID of the related Template
 * @property int $rank       The rank that this TV will show in relation to other TVs assigned to this Template
 * @property int $createdon  The UNIX time of when this TV-template relation was created
 * @property int $editedon   The UNIX time, if set, of when this TV-template relation was last edited
 *
 * @package MODX\Revolution
 */
class modTemplateVarTemplate extends xPDOObject
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
