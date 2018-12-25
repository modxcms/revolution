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
 * @package modx
 * @subpackage processors.system.filesys.file
 */

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

$file = $scriptProperties['path'].$scriptProperties['file'];

if (!file_exists($file)) return $modx->error->failure($modx->lexicon('file_err_nf'));

if (!@unlink($file)) return $modx->error->failure($modx->lexicon('file_err_remove'));

return $modx->error->success();
