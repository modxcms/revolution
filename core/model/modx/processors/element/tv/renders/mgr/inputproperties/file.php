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
 * @subpackage processors.element.tv.renders.mgr.inputproperties
 */
$modx->controller->setPlaceholder('base_url',$modx->getOption('base_url'));
return $modx->controller->fetchTemplate('element/tv/renders/inputproperties/file.tpl');
