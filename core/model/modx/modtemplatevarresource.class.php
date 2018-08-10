<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Stores the value of a TV for a specific Resource
 *
 * @property int $tmplvarid The ID of the related TV
 * @property int $contentid The ID of the related Resource
 * @property string $value The stored value of the TV for the Resource
 *
 * @see modTemplateVar
 * @see modResource
 * @package modx
 */
class modTemplateVarResource extends xPDOSimpleObject {}
