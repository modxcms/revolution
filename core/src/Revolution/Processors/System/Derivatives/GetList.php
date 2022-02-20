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
    const RESTRICT_ACTIONS = ['new', 'edit', 'delete'];

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
            'restrict_actions' => '',
        ]);
        return true;
    }

    /**
     * @param array $descendents
     * @return array
     */
    protected function checkRestrictions(array $descendents): array
    {
        $skip = explode(',', $this->getProperty('skip'));
        $actions = explode(',', $this->getProperty('restrict_actions'));

        $map = [
            'MODX\Revolution\modWebLink' => 'weblink',
            'MODX\Revolution\modSymLink' => 'symlink',
            'MODX\Revolution\modStaticResource' => 'static_resource',
        ];

        foreach ($actions as $action) {
            if (!in_array($action, self::RESTRICT_ACTIONS)) {
                continue;
            }

            foreach ($descendents as $descendent) {
                if ($descendent === 'MODX\Revolution\modDocument') {
                    continue;
                }

                if (!$this->modx->hasPermission($action . '_' . $map[$descendent])) {
                    $skip[] = $descendent;
                }
            }
        }

        return $skip;
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

        $descendants = $this->modx->getDescendants($class);
        $skip = $this->checkRestrictions($descendants);

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
