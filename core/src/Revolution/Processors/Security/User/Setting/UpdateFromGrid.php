<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\User\Setting;

/**
 * Updates a setting from a grid
 * @param integer $user The user to create the setting for
 * @param string $key The setting key
 * @param string $value The setting value
 * @package MODX\Revolution\Processors\Security\User\Setting
 */
class UpdateFromGrid extends Update
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
        $this->setDefaultProperties(['fk' => $this->getProperty('user')]);

        return parent::initialize();
    }
}
