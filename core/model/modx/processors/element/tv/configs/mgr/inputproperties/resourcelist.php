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

$showNone = $params['showNone'] == "false" || $params['showNone'] === 0 ? false : true ;
$includeParent = $params['includeParent'] == "false" || $params['includeParent'] === 0 ? false : true ;
$limitRelatedContext = $params['limitRelatedContext'] == "true" || $params['limitRelatedContext'] === 1 ? true : false ;
$depth = $params['depth'] === 0 || $params['depth'] >= 1 ? $params['depth'] : 10 ;
$limit = $params['limit'] >= 1 ? $params['limit'] : 0 ;

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
        'xtype' => 'combo-boolean',
        'fieldLabel' => $modx->lexicon('shownone'),
        'description' => $expandHelp ? '' : $modx->lexicon('shownone_desc'),
        'name' => 'inopt_showNone',
        'hiddenName' => 'inopt_showNone',
        'id' => 'inopt_showNone'.$tvId,
        'width' => 200,
        'value' => $showNone,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_showNone'.$tvId,
        'html' => $modx->lexicon('shownone_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'textfield',
        'fieldLabel' => $modx->lexicon('resourcelist_parents'),
        'description' => $expandHelp ? '' : $modx->lexicon('resourcelist_parents_desc'),
        'name' => 'inopt_parents',
        'id' => 'inopt_parents'.$tvId,
        'value' => $params['parents'],
        'anchor' => '100%',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_parents'.$tvId,
        'html' => $modx->lexicon('resourcelist_parents_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'textfield',
        'fieldLabel' => $modx->lexicon('resourcelist_depth'),
        'description' => $expandHelp ? '' : $modx->lexicon('resourcelist_depth_desc'),
        'name' => 'inopt_depth',
        'id' => 'inopt_depth'.$tvId,
        'value' => $depth,
        'width' => 200,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_depth'.$tvId,
        'html' => $modx->lexicon('resourcelist_depth_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'combo-boolean',
        'fieldLabel' => $modx->lexicon('resourcelist_includeparent'),
        'description' => $expandHelp ? '' : $modx->lexicon('resourcelist_includeparent_desc'),
        'name' => 'inopt_includeParent',
        'hiddenName' => 'inopt_includeParent',
        'id' => 'inopt_includeParent'.$tvId,
        'width' => 200,
        'value' => $includeParent,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_includeParent'.$tvId,
        'html' => $modx->lexicon('resourcelist_includeparent_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'combo-boolean',
        'fieldLabel' => $modx->lexicon('resourcelist_limitrelatedcontext'),
        'description' => $expandHelp ? '' : $modx->lexicon('resourcelist_limitrelatedcontext_desc'),
        'name' => 'inopt_limitRelatedContext',
        'hiddenName' => 'inopt_limitRelatedContext',
        'id' => 'inopt_limitRelatedContext'.$tvId,
        'width' => 200,
        'value' => $limitRelatedContext,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_limitRelatedContext'.$tvId,
        'html' => $modx->lexicon('resourcelist_limitrelatedcontext_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'textarea',
        'fieldLabel' => $modx->lexicon('resourcelist_where'),
        'description' => $expandHelp ? '' : $modx->lexicon('resourcelist_where_desc'),
        'name' => 'inopt_where',
        'id' => 'inopt_where'.$tvId,
        'value' => $params['where'],
        'anchor' => '100%',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_where'.$tvId,
        'html' => $modx->lexicon('resourcelist_where_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'fieldLabel' => $modx->lexicon('resourcelist_limit'),
        'description' => $expandHelp ? '' : $modx->lexicon('resourcelist_limit_desc'),
        'name' => 'inopt_limit',
        'id' => 'inopt_limit'.$tvId,
        'value' => $limit,
        'allowNegative' => false,
        'allowDecimals' => false,
        'width' => 200,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_limit'.$tvId,
        'html' => $modx->lexicon('resourcelist_limit_desc'),
        'cls' => 'desc-under'
    ]
];

return json_encode(['success' => 1, 'optsItems' => $optsItems]);
