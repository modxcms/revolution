<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\PropertySet;


use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\modPropertySet;

/**
 * Removes a property set
 *
 * @package MODX\Revolution\Processors\Element\PropertySet
 */
class Remove extends RemoveProcessor
{
    public $classKey = modPropertySet::class;
    public $objectType = 'propertyset';
    public $permission = 'delete_propertyset';
    public $languageTopics = ['propertyset'];
}
