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

$optsItems = [
    [
        'xtype' => 'combo-boolean',
        'fieldLabel' => $modx->lexicon('required'),
        'description' => $modx->lexicon('required_desc'),
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
        'fieldLabel' => $modx->lexicon('combo_listwidth'),
        'description' => $expandHelp ? '' : $modx->lexicon('combo_listwidth_desc'),
        'name' => 'inopt_listWidth',
        'id' => 'inopt_listWidth'.$tvId,
        'value' => $params['listWidth'],
        'width' => 200,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_listWidth'.$tvId,
        'html' => $modx->lexicon('combo_listwidth_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'allowNegative' => false,
        'allowDecimals' => false,
        'fieldLabel' => $modx->lexicon('combo_listheight'),
        'description' => $expandHelp ? '' : $modx->lexicon('combo_listheight_desc'),
        'name' => 'inopt_listHeight',
        'id' => 'inopt_listHeight'.$tvId,
        'value' => $params['listHeight'],
        'width' => 200,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_listHeight'.$tvId,
        'html' => $modx->lexicon('combo_listheight_desc'),
        'cls' => 'desc-under'
    ]
];

return json_encode(['success' => 1, 'optsItems' => $optsItems]);
