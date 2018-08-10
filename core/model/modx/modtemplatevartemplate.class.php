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
 * Represents a relationship between a template and a template variable. All TVs can be assigned to show on specified
 * Templates.
 *
 * @property int $tmplvarid The ID of the related TV
 * @property int $templateid The ID of the related Template
 * @property int $rank The rank that this TV will show in relation to other TVs assigned to this Template
 *
 * @see modTemplateVar
 * @see modTemplate
 * @package modx
 */
class modTemplateVarTemplate extends xPDOObject {}
