<?php

namespace MODX\Processors\Source;

/**
 * Update a Source from the grid. Sent through JSON-encoded 'data' parameter.
 *
 * @param integer $id The ID of the Source
 * @param string $name The new name
 * @param string $description (optional) A short description
 *
 * @package modx
 * @subpackage processors.source
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