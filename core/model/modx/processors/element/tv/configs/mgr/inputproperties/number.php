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

$allowDecimals = $params['allowDecimals'] == 'false' || $params['allowDecimals'] === 0 ? false : true ;
$allowNegative = $params['allowNegative'] == 'false' || $params['allowNegative'] === 0 ? false : true ;
$decimalPrecision = !empty($params['decimalPrecision']) ? $params['decimalPrecision'] : 2 ;
$decimalSeparator = !empty($params['decimalSeparator']) ? $params['decimalSeparator'] : '.' ;

/* Doing this to overcome issue with key being output when there is no lexicon entry for it */
$descKeys = ['allowdecimals_desc','allownegative_desc','decimalprecision_desc','decimalseparator_desc','minvalue_desc','maxvalue_desc'];
foreach ($descKeys as $k) {
    $txt = $modx->lexicon($k);
    $$k = $txt != $k ? $txt : '' ;
}

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
        'fieldLabel' => $modx->lexicon('number_allowdecimals'),
        // 'description' => $expandHelp ? '' : $modx->lexicon('allowdecimals_desc'),
        'description' => $expandHelp ? '' : $allowdecimals_desc,
        'name' => 'inopt_allowDecimals',
        'hiddenName' => 'inopt_allowDecimals',
        'id' => 'inopt_allowDecimals'.$tvId,
        'width' => 200,
        'value' => $allowDecimals,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_allowDecimals'.$tvId,
        // 'html' => $modx->lexicon('allowdecimals_desc'),
        'html' => $allowdecimals_desc,
        'cls' => 'desc-under'
    ],[
        'xtype' => 'combo-boolean',
        'fieldLabel' => $modx->lexicon('number_allownegative'),
        // 'description' => $expandHelp ? '' : $modx->lexicon('allownegative_desc'),
        'description' => $expandHelp ? '' : $allownegative_desc,
        'name' => 'inopt_allowNegative',
        'hiddenName' => 'inopt_allowNegative',
        'id' => 'inopt_allowNegative'.$tvId,
        'width' => 200,
        'value' => $allowNegative,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_allowNegative'.$tvId,
        // 'html' => $modx->lexicon('allownegative_desc'),
        'html' => $allownegative_desc,
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'fieldLabel' => $modx->lexicon('number_decimalprecision'),
        // 'description' => $expandHelp ? '' : $modx->lexicon('decimalprecision_desc'),
        'description' => $expandHelp ? '' : $decimalprecision_desc,
        'allowNegative' => false,
        'allowDecimals' => false,
        'name' => 'inopt_decimalPrecision',
        'id' => 'inopt_decimalPrecision'.$tvId,
        'value' => $decimalPrecision,
        'width' => 100,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_decimalPrecision'.$tvId,
        // 'html' => $modx->lexicon('decimalprecision_desc'),
        'html' => $decimalprecision_desc,
        'cls' => 'desc-under'
    ],[
        'xtype' => 'textfield',
        'fieldLabel' => $modx->lexicon('number_decimalseparator'),
        'description' => $expandHelp ? '' : $decimalseparator_desc,
        'name' => 'inopt_decimalSeparator',
        'id' => 'inopt_decimalSeparator'.$tvId,
        'value' => $decimalSeparator,
        'width' => 100,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_decimalSeparator'.$tvId,
        // 'html' => $modx->lexicon('decimalseparator_desc'),
        'html' => $decimalseparator_desc,
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'fieldLabel' => $modx->lexicon('number_minvalue'),
        'description' => $expandHelp ? '' : $minvalue_desc,
        'name' => 'inopt_minValue',
        'id' => 'inopt_minValue'.$tvId,
        'value' => $params['minValue'],
        'width' => 200,
        'validator' => 'minLtMax',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_minValue'.$tvId,
        // 'html' => $modx->lexicon('minvalue_desc'),
        'html' => $minvalue_desc,
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'fieldLabel' => $modx->lexicon('number_maxvalue'),
        'description' => $expandHelp ? '' : $maxvalue_desc,
        'name' => 'inopt_maxValue',
        'id' => 'inopt_maxValue'.$tvId,
        'value' => $params['maxValue'],
        'width' => 200,
        'validator' => 'maxGtMin',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_maxValue'.$tvId,
        // 'html' => $modx->lexicon('maxvalue_desc'),
        'html' => $maxvalue_desc,
        'cls' => 'desc-under'
    ]
];

return json_encode(['success' => 1, 'optsItems' => $optsItems]);
