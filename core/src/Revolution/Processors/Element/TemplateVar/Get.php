<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar;


use MODX\Revolution\modTemplateVar;

/**
 * Gets a TV
 *
 * @property integer $id The ID of the TV
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar
 */
class Get extends \MODX\Revolution\Processors\Element\Get
{
    public $classKey = modTemplateVar::class;
    public $languageTopics = ['tv', 'category'];
    public $permission = 'view_tv';
    public $objectType = 'tv';

    public function beforeOutput()
    {
        parent::beforeOutput();
        $this->object->set('els', $this->object->get('elements'));
    }
}

