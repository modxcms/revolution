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
 * A many-to-many relationship between Resources and Resource Groups.
 *
 * @property int $document_group The ID of the Resource Group
 * @property int $document The ID of the Resource
 * @see modResource
 * @see modResourceGroup
 * @package modx
 */
class modResourceGroupResource extends xPDOSimpleObject {}
