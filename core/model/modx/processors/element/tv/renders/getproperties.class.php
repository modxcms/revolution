<?php
require_once dirname(__FILE__).'/getinputproperties.class.php';
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

class modTvRendersGetOutputPropertiesProcessor extends modTvRendersGetPropertiesProcessor {
    public $propertiesKey = 'output_properties';
    public $renderDirectory = 'properties';
    public $onPropertiesListEvent = 'OnTVOutputRenderPropertiesList';
}

return 'modTvRendersGetOutputPropertiesProcessor';
