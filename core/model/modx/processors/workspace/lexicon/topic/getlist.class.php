<?php
/**
 * Gets a list of lexicon topics
 *
 * @param string $namespace (optional) Filters by this namespace. Defaults to
 * core.
 * @param string $name (optional) If set, will search by this name.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
class modLexiconTopicGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('lexicons');
    }
    public function getLanguageTopics() {
        return array('lexicon');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 0,
            'start' => 0,
            'namespace' => 'core',
            'language' => 'en',
        ));
        return parent::initialize();
    }

    public function process() {
        $data = $this->getData();

        $list = array();
        foreach ($data['results'] as $topic) {
            $list[] = array(
                'name' => $topic,
            );
        }
        return $this->outputArray($list,$data['total']);
    }

    public function getData() {
        $data = array();
        $data['results'] = $this->modx->lexicon->getTopicList($this->getProperty('language'),$this->getProperty('namespace'));
        $data['total'] = count($data['results']);

        // this allows for typeahead filtering in the lexicon topics combobox
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $data['results'] = preg_grep('/' . $query . '/i', $data['results']);
            $data['total'] = count($data['results']);
        }

        $limit = $this->getProperty('limit');
        if ($limit > 0) {
            $data['results'] = array_slice($data['results'],$this->getProperty('start'),$limit,true);
        }

        return $data;
    }
}
return 'modLexiconTopicGetListProcessor';
