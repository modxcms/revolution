<?php
/**
 * Grabs a list of elements by their subclass
 *
 * @package modx
 * @subpackage processors.element
 */
class modElementGetListByClass extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('class_map');
    }
    public function getLanguageTopics() {
        return array('propertyset','element');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
            'start' => 0,
            'sort' => 'name',
            'dir' => 'ASC',
            'element_class' => false,
        ));
        return true;
    }

    public function process() {
        $className = $this->getProperty('element_class');
        if (empty($className)) return $this->failure($this->modx->lexicon('element_class_ns'));
        $data = $this->getElements($className);

        $list = array();
        /** @var modElement $element */
        foreach ($data['results'] as $element) {
            $elementArray = $element->toArray();
            $elementArray['name'] = isset($elementArray['templatename']) ? $elementArray['templatename'] : $elementArray['name'];
            $list[] = $elementArray;
        }

        return $this->outputArray($list,$data['total']);
    }

    public function getElements($className) {
        /* get default properties */
        $limit = $this->getProperty('limit',10);
        $sort = $this->getProperty('sort','name');
        $isLimit = !empty($limit);
        /* fix for template's different name field */
        if ($className == 'modTemplate' && $sort == 'name') $sort = 'templatename';

        $c = $this->modx->newQuery($className);
        $data['total'] = $this->modx->getCount($className,$c);

        $c->sortby($sort,$this->getProperty('dir','ASC'));
        if ($isLimit) $c->limit($limit,$this->getProperty('start',0));
        $data['results'] = $this->modx->getCollection($className,$c);
        return $data;
    }
}
return 'modElementGetListByClass';