<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access\Policy\Template;

use xPDO\xPDOException;

/**
 * Update a policy template from a grid
 *
 * @param int    $id          The ID of the policy
 * @param string $name        The name of the policy.
 * @param string $description (optional) A short description
 *
 * @package MODX\Revolution\Processors\Security\Access\Policy\Template
 */
class UpdateFromGrid extends Update
{
    /**
     * @return bool|string|null
     * @throws xPDOException
     */
    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $data = $this->modx->fromJSON($data);
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $this->setProperties($data);
        $this->unsetProperty('data');

        $this->setProperty(
            'description_trans',
            $this->modx->lexicon($this->getProperty('description'))
        );

        return parent::initialize();
    }
}
