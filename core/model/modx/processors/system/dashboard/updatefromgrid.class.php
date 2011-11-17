<?php
require_once (dirname(__FILE__).'/update.class.php');
/**
 * Update a Dashboard from the grid. Sent through JSON-encoded 'data' parameter.
 *
 * @param integer $id The ID of the Dashboard
 * @param string $name The new name
 * @param string $description (optional) A short description
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 * 
 * @package modx
 * @subpackage processors.system.dashboard
 */
 class modDashboardUpdateFromGridProcessor extends modDashboardUpdateProcessor {
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $data = $this->modx->fromJSON($data);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
return 'modDashboardUpdateFromGridProcessor';