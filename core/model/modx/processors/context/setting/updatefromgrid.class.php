<?php
require_once (dirname(__FILE__) . '/update.class.php');
/**
 * Updates a setting from a grid. Passed as JSON data.
 *
 * @param string $context_key The key of the context
 * @param string $key The key of the setting
 * @param string $value The value of the setting.
 *
 * @package modx
 * @subpackage processors.context.setting
 */

class modContextSettingUpdateFromGridProcessor extends modContextSettingUpdateProcessor {
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $properties = $this->modx->fromJSON($data);
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}
return 'modContextSettingUpdateFromGridProcessor';
