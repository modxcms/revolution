<?php
require_once (dirname(__FILE__) . '/update.class.php');
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

class modUserSettingUpdateFromGridProcessor extends modUserSettingUpdateProcessor {
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $properties = $this->modx->fromJSON($data);
        $this->setProperties($properties);
        $this->unsetProperty('data');
        $this->setDefaultProperties(array(
            'fk' => $this->getProperty('user')
        ));

        return parent::initialize();
    }
}

return 'modUserSettingUpdateFromGridProcessor';
