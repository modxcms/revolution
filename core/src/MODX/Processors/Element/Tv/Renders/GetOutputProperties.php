<?php

namespace MODX\Processors\Element\Tv\Renders;

/**
 * Grabs a list of render properties for a TV render
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 * @param string $type (optional) The type of render to grab properties for.
 * Defaults to default.
 * @param integer $tv (optional) The TV to prefill property values from.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */

class GetOutputProperties extends GetInputProperties
{
    public $propertiesKey = 'output_properties';
    public $renderDirectory = 'OutputProperties';
    public $onPropertiesListEvent = 'OnTVOutputRenderPropertiesList';
}