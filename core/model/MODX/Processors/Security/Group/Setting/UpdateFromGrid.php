<?php

namespace MODX\Processors\Security\Group\Setting;

/**
 * Update a user group setting from a grid
 *
 * @param integer $group The group to create the setting for
 * @param string $key The setting key
 * @param string $value The setting value
 *
 * @package modx
 * @subpackage processors.security.group.setting
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
        $this->setDefaultProperties([
            'fk' => $this->getProperty('group'),
        ]);

        return parent::initialize();
    }
}