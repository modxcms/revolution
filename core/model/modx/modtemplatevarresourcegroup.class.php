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
 * A relation between Template Variables and Resource Groups. Only user groups with the specified Resource Groups, if
 * any are set, will be able to edit the TV.
 *
 * @property int $tmplvarid The ID of the related TV
 * @property int $documentgroup The ID of the related Resource Group
 *
 * @see modResourceGroup
 * @see modTemplateVar
 * @package modx
 */
class modTemplateVarResourceGroup extends xPDOSimpleObject {}
