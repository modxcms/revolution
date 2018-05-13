<?php

namespace MODX\Processors\Element\Template;

/**
 * Gets a template
 *
 * @param integer $id The ID of the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
class Get extends \MODX\Processors\Element\Get
{
    public $classKey = 'modTemplate';
    public $languageTopics = ['template', 'category'];
    public $permission = 'view_template';
    public $objectType = 'template';
}