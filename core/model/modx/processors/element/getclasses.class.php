<?php
/**
 * Outputs a list of Element subclasses
 *
 * @deprecated Use $modx->getDescendants($className) now
 * 
 * @package modx
 * @subpackage processors.element
 */
class modElementGetClassesProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_element');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
            'start' => 0,
            'sort' => 'class',
            'dir' => 'ASC',
        ));
        return true;
    }

    public function process() {
        $limit = $this->getProperty('limit',10);
        $isLimit = !empty($limit);
                
        /* build query */
        $c = $this->modx->newQuery('modClassMap');
        $c->where(array(
            'parent_class' => 'modElement',
            'class:!=' => 'modTemplate',
        ));
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($isLimit) $c->limit($limit,$this->getProperty('start'));
        $classes = $this->modx->getCollection('modClassMap',$c);

        /* iterate */
        $list = array();
        /** @var modClassMap $class */
        foreach ($classes as $class) {
            $el = array( 'name' => $class->get('class') );
            $list[] = $el;
        }
        return $this->outputArray($list);
    }
}
return 'modElementGetClassesProcessor';