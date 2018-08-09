<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Gets a list of derivative classes for a class
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.derivatives
 */
class modSystemDerivativesGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('class_map');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'class' => '',
            'skip' => 'modXMLRPCResource',
        ));
        return true;
    }
    public function process() {
        $class = $this->getProperty('class');
        if (empty($class)) $this->failure($this->modx->lexicon('class_err_ns'));

        $skip = explode(',',$this->getProperty('skip'));
        $descendants = $this->modx->getDescendants($class);

        $list = array();
        foreach ($descendants as $descendant) {
            if (in_array($descendant,$skip)) continue;

            /** @var xPDOObject|modResource $obj */
            $obj = $this->modx->newObject($descendant);
            if (!$obj) continue;

            if ($class == 'modResource' && !$obj->allowListingInClassKeyDropdown) continue;
            if ($class == 'modResource') {
                $name = $obj->getResourceTypeName();
            } else {
                $name = $descendant;
            }

            $list[] = array(
                'id' => $descendant,
                'name' => $name,
            );
        }

        return $this->outputArray($list);
    }
}
return 'modSystemDerivativesGetListProcessor';
