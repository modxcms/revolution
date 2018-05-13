<?php

namespace MODX\Processors\System\Derivatives;

use MODX\modResource;
use MODX\Processors\modProcessor;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of derivative classes for a class
 *
 * @package modx
 * @subpackage processors.system.derivatives
 */
class GetList extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('class_map');
    }


    public function initialize()
    {
        $this->setDefaultProperties([
            'class' => '',
        ]);

        return true;
    }


    public function process()
    {
        $class = $this->getProperty('class');
        if (empty($class)) {
            $this->failure($this->modx->lexicon('class_err_ns'));
        }

        $skip = explode(',', $this->getProperty('skip'));

        $list = [];
        $descendants = $this->modx->getDescendants($class);
        foreach ($descendants as $descendant) {
            $descendant = str_replace('MODX\\', '', $descendant);
            if (in_array($descendant, $skip)) {
                continue;
            }

            /** @var xPDOObject|modResource $obj */
            $obj = $this->modx->newObject($descendant);
            if (!$obj) continue;

            if ($class == 'modResource' && !$obj->allowListingInClassKeyDropdown) {
                continue;
            }
            if ($class == 'modResource') {
                $name = $obj->getResourceTypeName();
            } else {
                $name = $descendant;
            }

            $list[] = [
                'id' => $descendant,
                'name' => $name,
            ];
        }

        return $this->outputArray($list);
    }
}
