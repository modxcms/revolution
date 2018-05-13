<?php

namespace MODX\Processors\System\Settings;


/**
 * Update a setting from a grid
 *
 * @param string $key The key of the setting
 * @param string $oldkey The old key of the setting
 * @param string $value The value of the setting
 * @param string $area The area for the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 *
 * @package modx
 * @subpackage processors.system.settings
 */
class UpdateFromGrid extends Update
{
    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $properties = json_decode($data, true);
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}