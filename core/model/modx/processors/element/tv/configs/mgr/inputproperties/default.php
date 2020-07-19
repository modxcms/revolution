<?php
/*
 * This file is part of a proposed change to MODX Revolution's tv input option rendering in the back end.
 * Developed by Jim Graham, Spark Media Group (smg6511)
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package modx
 * @subpackage processors.element.tv.configs.mgr.inputproperties
 */

# This type has no custom options
$optsItems = [];

return json_encode(['success' => 1, 'optsItems' => $optsItems]);
