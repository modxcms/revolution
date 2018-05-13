<?php

namespace MODX\Processors\Security\User\Settings;
/**
 * Updates a setting from a grid
 *
 * @param integer $user The user to create the setting for
 * @param string $key The setting key
 * @param string $value The setting value
 *
 * @package modx
 * @subpackage processors.security.user.setting
 */

class UpdateFromGrid extends \MODX\Processors\Security\User\Update
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
            'fk' => $this->getProperty('user'),
        ]);

        return parent::initialize();
    }
}