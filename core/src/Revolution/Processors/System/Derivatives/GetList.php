<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Derivatives;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of derivative classes for a class
 * @package MODX\Revolution\Processors\System\Derivatives
 */
class GetList extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('class_map');
    }

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'class' => '',
        ]);
        return true;
    }

    /**
     * @return mixed|string
     */
    public function process()
    {
        $class = $this->getProperty('class');
        if (empty($class)) {
            $this->failure($this->modx->lexicon('class_err_ns'));
        }

        $skip = explode(',', $this->getProperty('skip'));
        $descendants = $this->modx->getDescendants($class);

        $list = [];
        foreach ($descendants as $descendant) {
            if (in_array($descendant, $skip, true)) {
                continue;
            }

            /** @var xPDOObject|modResource $obj */
            $obj = $this->modx->newObject($descendant);
            if (!$obj) {
                continue;
            }

            if ($class === modResource::class && !$obj->allowListingInClassKeyDropdown) {
                continue;
            }
            if ($class === modResource::class) {
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
