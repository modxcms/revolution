<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Template;


use MODX\Revolution\modTemplate;

/**
 * Gets a template
 *
 * @property integer $id The ID of the template
 *
 * @package MODX\Revolution\Processors\Element\Template
 */
class Get extends \MODX\Revolution\Processors\Element\Get
{
    public $classKey = modTemplate::class;
    public $languageTopics = ['template', 'category'];
    public $permission = 'view_template';
    public $objectType = 'template';
}
