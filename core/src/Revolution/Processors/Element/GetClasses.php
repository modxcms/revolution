<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element;

use MODX\Revolution\modElement;
use MODX\Revolution\Processors\Processor;

/**
 * Outputs a list of Element subclasses
 *
 * @deprecated Use $modx->getDescendants($className) now
 *
 * @package    MODX\Revolution\Processors\Element
 */
class GetClasses extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_element');
    }

    public function initialize()
    {
        $this->setDefaultProperties([
            'limit' => 0,
            'start' => 0,
            'sort' => 'class',
            'dir' => 'ASC',
        ]);

        return true;
    }

    public function process()
    {
        $classes = $this->modx->getDescendants(modElement::class);

        $list = [];

        foreach ($classes as $class) {
            if ($class === 'MODX\\Revolution\\modScript') continue;

            $list[] = ['name' => $class];
        }

        return $this->outputArray($list);
    }
}
