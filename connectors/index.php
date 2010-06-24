<?php
/*
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */
/**
 * @package modx
 * @subpackage connectors
 */
@include(dirname(__FILE__) . '/config.core.php');
if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');
if (!include_once(MODX_CORE_PATH . 'model/modx/modx.class.php')) die();
error_reporting(E_ALL); ini_set('display_errors',true);

/* instantiate the modX class with the appropriate configuration */
if (empty($options) || !is_array($options)) $options = array();
$modx= new modX('', $options);

/* set debugging/logging options */
$modx->setDebug(E_ALL & ~E_NOTICE);
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

/* initialize the proper context */
$ctx = isset($_REQUEST['ctx']) && !empty($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'mgr';
$modx->initialize($ctx);
if (defined('MODX_REQP') && MODX_REQP === false) {
} else if (!$modx->context->checkPolicy('load')) { die(); }

if ($ctx == 'mgr') {
    $ml = $modx->getOption('manager_language',null,'en');
    if ($ml != 'en') {
        $modx->lexicon->load($ml.':core:default');
        $modx->setOption('cultureKey',$ml);
    }
}

/* handle the request */
$connectorRequestClass = $modx->getOption('modConnectorRequest.class',null,'modConnectorRequest');
$modx->config['modRequest.class'] = $connectorRequestClass;
$modx->getRequest();
$modx->request->sanitizeRequest();