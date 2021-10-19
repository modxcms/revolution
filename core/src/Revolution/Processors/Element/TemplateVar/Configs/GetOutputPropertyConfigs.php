<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar\Configs;

/**
 * Grabs a list of render properties for a TV render
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 * @param string $type (optional) The type of render to grab properties for.
 * Defaults to default.
 * @param integer $tv (optional) The TV to prefill property values from.
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar\Renders
 */
class GetOutputPropertyConfigs extends GetInputPropertyConfigs
{
    public $propertiesKey = 'output_properties';
    public $configDirectory = 'properties';
    public $onPropertiesListEvent = 'OnTVOutputRenderPropertiesList';
}
