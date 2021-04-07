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
 * @var modX $modx
 */
define('MODX_CONNECTOR_INCLUDED', 1);
require_once __DIR__ .'/index.php';
$modx->request->handleRequest(array('location' => 'system','action' => 'config.js'));
