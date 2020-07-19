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

$typeAhead = $params['typeAhead'] == "true" || $params['typeAhead'] === 1 ? true : false ;
$stackItems = $params['stackItems'] == "true" || $params['stackItems'] === 1 ? true : false ;

// Should there be a minimum set here? Should it ever be 0?
$typeAheadDelay = !empty($params['typeAheadDelay']) ? $params['typeAheadDelay'] : 250 ;

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
        'xtype' => 'textfield',
        'fieldLabel' => $modx->lexicon('combo_title'),
        'description' => $expandHelp ? '' : $modx->lexicon('combo_title_desc'),
        'name' => 'inopt_title',
        'id' => 'inopt_title'.$tvId,
        'value' => $params['title'],
        'anchor' => '100%',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_title'.$tvId,
        'html' => $modx->lexicon('combo_title_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'combo-boolean',
        'fieldLabel' => $modx->lexicon('combo_typeahead'),
        'description' => $expandHelp ? '' : $modx->lexicon('combo_typeahead_desc'),
        'name' => 'inopt_typeAhead',
        'hiddenName' => 'inopt_typeAhead',
        'id' => 'inopt_typeAhead'.$tvId,
        'width' => 200,
        'value' => $typeAhead,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_typeAhead'.$tvId,
        'html' => $modx->lexicon('combo_typeahead_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'allowNegative' => false,
        'allowDecimals' => false,
        'fieldLabel' => $modx->lexicon('combo_typeahead_delay'),
        'description' => $expandHelp ? '' : $modx->lexicon('combo_typeahead_delay_desc'),
        'name' => 'inopt_typeAheadDelay',
        'id' => 'inopt_typeAheadDelay'.$tvId,
        'value' => $typeAheadDelay,
        'width' => 200,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_typeAheadDelay'.$tvId,
        'html' => $modx->lexicon('combo_typeahead_delay_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'textfield',
        'fieldLabel' => $modx->lexicon('combo_listempty_text'),
        'description' => $expandHelp ? '' : $modx->lexicon('combo_listempty_text_desc'),
        'name' => 'inopt_listEmptyText',
        'id' => 'inopt_listEmptyText'.$tvId,
        'value' => $params['listEmptyText'],
        'anchor' => '100%',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_listEmptyText'.$tvId,
        'html' => $modx->lexicon('combo_listempty_text_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'combo-boolean',
        'fieldLabel' => $modx->lexicon('combo_stackitems'),
        'description' => $expandHelp ? '' : $modx->lexicon('combo_stackitems_desc'),
        'name' => 'inopt_stackItems',
        'hiddenName' => 'inopt_stackItems',
        'id' => 'inopt_stackItems'.$tvId,
        'width' => 200,
        'value' => $stackItems,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_stackItems'.$tvId,
        'html' => $modx->lexicon('combo_stackitems_desc'),
        'cls' => 'desc-under'
    ]
];

return json_encode(['success' => 1, 'optsItems' => $optsItems]);
