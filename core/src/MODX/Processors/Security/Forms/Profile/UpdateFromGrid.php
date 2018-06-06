<?php

namespace MODX\Processors\Security\Forms\Profile;

/**
 * Update a FC Profile from grid
 *
 * @package modx
 * @subpackage processors.security.forms.profile
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