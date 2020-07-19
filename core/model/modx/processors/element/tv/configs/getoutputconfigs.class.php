<?php
/*
 * This file is part of a proposed change to MODX Revolution's tv input/output option rendering in the back end.
 * Developed by Jim Graham (smg6511), Pixels+Strings, LLC
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once dirname(__FILE__).'/getinputconfigs.class.php';
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

class modTvConfigsGetOutputPropertiesProcessor extends modTvConfigsGetPropertiesProcessor {
    public $propertiesKey = 'output_properties';
    public $renderDirectory = 'properties';
    public $onPropertiesListEvent = 'OnTVOutputRenderPropertiesList';
}

return 'modTvConfigsGetOutputPropertiesProcessor';
