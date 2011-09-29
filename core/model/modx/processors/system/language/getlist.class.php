<?php
/**
 * Grabs a list of lexicon languages
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 *
 * @package modx
 * @subpackage processors.system.language
 */
class modSystemLanguageGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('languages');
    }
    public function getLanguageTopics() {
        return array('lexicon');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'namespace' => 'core',
        ));
        return true;
    }
    public function process() {
        $data = $this->getData();
        if (empty($data)) return $this->failure();
        
        /* loop through */
        $list = array();
        foreach ($data['results'] as $language) {
            $list[] = array(
                'name' => $language,
            );
        }

        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get a collection of languages
     * @return array
     */
    public function getData() {
        $data = array();

        $limit = $this->getProperty('limit',10);
        $isLimit = !empty($limit);

        $data['results'] = $this->modx->lexicon->getLanguageList($this->getProperty('namespace'));
        $data['total'] = count($data['results']);

        if ($isLimit) {
            $data['results'] = array_slice($data['results'],$this->getProperty('start'),$limit,true);
        }
        return $data;
    }
}
return 'modSystemLanguageGetListProcessor';