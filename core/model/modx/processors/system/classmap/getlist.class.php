<?php
/**
 * Gets a list of classes in the class map
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to class.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.classmap
 */
class modClassMapGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modClassMap';
    public $permission = 'class_map';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $parentClass = $this->getProperty('parentClass','');
        if (!empty($parentClass)) {
            $c->where(array(
                'parent_class' => $parentClass,
            ));
        }
        return $c;
    }
}
return 'modClassMapGetListProcessor';