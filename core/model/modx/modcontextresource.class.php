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
 * Represents a relationship between a modContext and a modResource.
 *
 * @property string $context_key The key of the Context
 * @property int $resource The ID of the related Resource
 *
 * @package modx
 * @todo Work this relationship into use in the manager and the logic of each
 * {@link modResource::process()} implementation.
 */
class modContextResource extends xPDOObject {}
