<?php
/*
 * This file is part of a proposed change to MODX Revolution's tv input/output option rendering in the back end.
 * Developed by Jim Graham (smg6511), Pixels+Strings, LLC (formerly Spark Media Group)
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

$columns = !empty($params['columns']) ? $params['columns'] : 1 ;

$optsItems = [
    [
        'xtype' => 'combo-boolean',
        'fieldLabel' => $modx->lexicon('required'),
        'description' => $expandHelp ? '' : $modx->lexicon('required_desc'),
        'name' => 'inopt_allowBlank',
        'hiddenName' => 'inopt_allowBlank',
        'id' => 'inopt_allowBlank'.$tvId,
        'width' => 200,
        'value' => $allowBlank,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_allowBlank'.$tvId,
        'html' => $modx->lexicon('required_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'allowNegative' => false,
        'allowDecimals' => false,
        'fieldLabel' => $modx->lexicon('checkbox_columns'),
        'description' => $expandHelp ? '' : $modx->lexicon('checkbox_columns_desc'),
        'name' => 'inopt_columns',
        'id' => 'inopt_columns'.$tvId,
        'value' => $columns,
        'width' => 300,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_columns'.$tvId,
        'html' => $modx->lexicon('checkbox_columns_desc'),
        'cls' => 'desc-under'
    ]
];

return json_encode(['success' => 1, 'optsItems' => $optsItems]);
