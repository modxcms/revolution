<?php

namespace MODX\Processors\System\Dashboard;

/**
 * Update a Dashboard from the grid. Sent through JSON-encoded 'data' parameter.
 *
 * @param integer $id The ID of the Dashboard
 * @param string $name The new name
 * @param string $description (optional) A short description
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
class UpdateFromGrid extends Update
{
    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $data = json_decode($data, true);
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $this->setProperties($data);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}