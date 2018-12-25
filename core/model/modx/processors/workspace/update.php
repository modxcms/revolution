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
 * Updates a workspace
 *
 * @package modx
 * @subpackage processors.workspace
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('workspaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

return $modx->error->success();
