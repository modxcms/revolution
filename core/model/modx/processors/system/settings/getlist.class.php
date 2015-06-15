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
class modSystemSettingsGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modSystemSetting';
    public $languageTopics = array('setting','namespace');
    public $permission = 'settings';
    public $defaultSortField = 'key';

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'key' => false,
            'namespace' => false,
            'area' => false,
            'dateFormat' => $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format'),
        ));
        return $initialized;
    }

    /**
     * For derivative criteria
     * @return array
     */
    public function prepareCriteria() {
        return array();
    }

    /**
     * Get a collection of modSystemSetting objects
     * @return array
     */
    public function getData() {
        $key = $this->getProperty('key',false);
        $data = array();

        $criteria = $this->prepareCriteria();
        if (!empty($key)) {
            $criteria[] = array(
                $this->classKey.'.key:LIKE' => '%'.$key.'%',
                'OR:Entry.value:LIKE' => '%'.$key.'%',
                'OR:'.$this->classKey.'.value:LIKE' => '%'.$key.'%',
                'OR:Description.value:LIKE' => '%'.$key.'%',
            );
        }

        $namespace = $this->getProperty('namespace',false);
        if (!empty($namespace)) {
            /** @var modNamespace $namespaceObject */
            $namespaceObject = $this->modx->getObject('modNamespace', $namespace);
            if (!$namespaceObject) {
                $criteria[] = array('1 != 1');
            }
            $criteria[] = array('namespace' => $namespace);
        }

        $area = $this->getProperty('area',false);
        if (!empty($area)) {
            $criteria[] = array('area' => $area);
        }

        $settingsResult = $this->modx->call($this->classKey, 'listSettings', array(
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
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $settingArray = $object->toArray();
        $k = 'setting_'.$settingArray['key'];

        /* if 3rd party setting, load proper text, fallback to english */
        $this->modx->lexicon->load('en:'.$object->get('namespace').':default','en:'.$object->get('namespace').':setting');
        $this->modx->lexicon->load($object->get('namespace').':default',$object->get('namespace').':setting');

        /* get translated area text */
        if ($this->modx->lexicon->exists('area_'.$object->get('area'))) {
            $settingArray['area_text'] = $this->modx->lexicon('area_'.$object->get('area'));
        } else {
            $settingArray['area_text'] = $settingArray['area'];
        }

        /* get translated name and description text */
        if (empty($settingArray['description_trans'])) {
            if ($this->modx->lexicon->exists($k.'_desc')) {
                $settingArray['description_trans'] = $this->modx->lexicon($k.'_desc');
                $settingArray['description'] = $k.'_desc';
            } else {
                $settingArray['description_trans'] = !empty($settingArray['description']) ? $settingArray['description'] : '';
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

        $settingArray['editedon'] = in_array(
            $object->get('editedon'),
            array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null)
        ) ? '' : date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));

        return $settingArray;
    }
}
return 'modSystemSettingsGetListProcessor';

