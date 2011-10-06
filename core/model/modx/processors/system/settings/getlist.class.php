<?php
/**
 * Get a list of system settings
 *
 * @param string $key (optional) If set, will search by this value
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.settings
 */
class modSystemSettingsGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('settings');
    }
    public function getLanguageTopics() {
        return array('setting','namespace');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 20,
            'start' => 0,
            'sort' => 'key',
            'dir' => 'ASC',
            'key' => false,
            'namespace' => false,
            'area' => false,
            'dateFormat' => '%b %d, %Y %I:%M %p',
        ));
        return true;
    }

    public function process() {
        $data = $this->getData();
        if (empty($data)) return $this->failure();

        $list = array();
        foreach ($data['results'] as $setting) {
            $settingArray = $this->prepareSetting($setting);
            if (!empty($settingArray)) {
                $list[] = $settingArray;
            }
        }

        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get a collection of modSystemSetting objects
     * @return array
     */
    public function getData() {
        $key = $this->getProperty('key',false);
        $data = array();
        
        $criteria = array();
        if (!empty($key)) {
            $criteria[] = array(
                'modSystemSetting.key:LIKE' => '%'.$key.'%',
                'OR:Entry.value:LIKE' => '%'.$key.'%',
                'OR:modSystemSetting.value:LIKE' => '%'.$key.'%',
                'OR:Description.value:LIKE' => '%'.$key.'%',
            );
        }

        $namespace = $this->getProperty('namespace',false);
        if (!empty($namespace)) {
            $criteria[] = array('namespace' => $namespace);
        }

        $area = $this->getProperty('area',false);
        if (!empty($area)) {
            $criteria[] = array('area' => $area);
        }

        $settingsResult = $this->modx->call('modSystemSetting', 'listSettings', array(
            &$this->modx,
            $criteria,
            array(
                $this->getProperty('sort') => $this->getProperty('dir'),
            ),
            $this->getProperty('limit'),
            $this->getProperty('start'),
        ));
        $data['total'] = $settingsResult['count'];
        $data['results'] = $settingsResult['collection'];
        return $data;
    }

    /**
     * Prepare a setting for output
     * 
     * @param modSystemSetting $setting
     * @return array
     */
    public function prepareSetting(modSystemSetting $setting) {
        $settingArray = $setting->toArray();
        $k = 'setting_'.$settingArray['key'];

        /* if 3rd party setting, load proper text, fallback to english */
        $this->modx->lexicon->load('en:'.$setting->get('namespace').':default');
        $this->modx->lexicon->load($setting->get('namespace').':default');

        /* get translated area text */
        if ($this->modx->lexicon->exists('area_'.$setting->get('area'))) {
            $settingArray['area_text'] = $this->modx->lexicon('area_'.$setting->get('area'));
        } else {
            $settingArray['area_text'] = $settingArray['area'];
        }

        /* get translated name and description text */
        if (empty($settingArray['description_trans'])) {
            if ($this->modx->lexicon->exists($k.'_desc')) {
                $settingArray['description_trans'] = $this->modx->lexicon($k.'_desc');
                $settingArray['description'] = $k.'_desc';
            } else {
                $settingArray['description_trans'] = $settingArray['description'];
            }
        } else {
            $settingArray['description'] = $settingArray['description_trans'];
        }
        if (empty($settingArray['name_trans'])) {
            if ($this->modx->lexicon->exists($k)) {
                $settingArray['name_trans'] = $this->modx->lexicon($k);
                $settingArray['name'] = $k;
            } else {
                $settingArray['name_trans'] = $settingArray['key'];
            }
        } else {
            $settingArray['name'] = $settingArray['name_trans'];
        }

        $settingArray['oldkey'] = $settingArray['key'];

        $settingArray['editedon'] = $setting->get('editedon') == '-001-11-30 00:00:00' || $settingArray['editedon'] == '0000-00-00 00:00:00' || $settingArray['editedon'] == null
            ? ''
            : strftime($this->getProperty('dateFormat','%b %d, %Y %I:%M %p'),strtotime($setting->get('editedon')));

        return $settingArray;
    }
}
return 'modSystemSettingsGetListProcessor';

