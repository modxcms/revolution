<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Role;


use modUserGroupRoleUpdateProcessor;

/**
 * Updates a role from a grid. Passed as JSON data
 * @param integer $id The ID of the role
 * @package MODX\Revolution\Processors\Security\Role
 */
class UpdateFromGrid extends modUserGroupRoleUpdateProcessor
{
    /**
     * @return bool|string|null
     * @throws \xPDO\xPDOException
     */
    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $properties = $this->modx->fromJSON($data);
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
