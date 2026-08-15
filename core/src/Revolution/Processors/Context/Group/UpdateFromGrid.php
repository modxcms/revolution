<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context\Group;

/**
 * Update a Context Group from a grid row (JSON data).
 *
 * @package MODX\Revolution\Processors\Context\Group
 */
class UpdateFromGrid extends Update
{
    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $properties = $this->modx->fromJSON($data);
        if (empty($properties) || !is_array($properties)) {
            return $this->modx->lexicon('invalid_data');
        }
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
