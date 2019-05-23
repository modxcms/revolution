<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context\Setting;


/**
 * Updates a setting from a grid. Passed as JSON data.
 *
 * @property string $context_key The key of the context
 * @property string $key         The key of the setting
 * @property string $value       The value of the setting.
 *
 * @package MODX\Revolution\Processors\Context\Setting
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
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
