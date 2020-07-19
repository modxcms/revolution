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

/* Doing this to overcome issue with key being output when there is no lexicon entry for it */
$descKeys = ['min_length_desc','max_length_desc'];
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
        'xtype' => 'numberfield',
        'fieldLabel' => $modx->lexicon('min_length'),
        'description' => $expandHelp ? '' : $min_length_desc,
        'name' => 'inopt_minLength',
        'id' => 'inopt_minLength'.$tvId,
        'value' => $params['minLength'],
        'width' => 200,
        'allowDecimals' => false,
        'allowNegative' => false,
        'validator' => 'minLtMax',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_minLength'.$tvId,
        // 'html' => $modx->lexicon('min_length_desc'),
        'html' => $min_length_desc,
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'fieldLabel' => $modx->lexicon('max_length'),
        'description' => $expandHelp ? '' : $max_length_desc,
        'name' => 'inopt_maxLength',
        'id' => 'inopt_maxLength'.$tvId,
        'value' => $params['maxLength'],
        'width' => 200,
        'allowDecimals' => false,
        'allowNegative' => false,
        'validator' => 'maxGtMin',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_maxLength'.$tvId,
        // 'html' => $modx->lexicon('max_length_desc'),
        'html' => $max_length_desc,
        'cls' => 'desc-under'
    ],[
        'xtype' => 'textfield',
        'fieldLabel' => $modx->lexicon('regex'),
        'name' => 'inopt_regex',
        'id' => 'inopt_regex'.$tvId,
        'value' => $params['regex'],
        'width' => 200,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => 'textfield',
        'fieldLabel' => $modx->lexicon('regex_text'),
        'name' => 'inopt_regexText',
        'id' => 'inopt_regexText'.$tvId,
        'value' => $params['regexText'],
        'width' => 200,
        'listeners' => 'dirtyOnChange,alertOnFocus'
    ]
];

return json_encode(['success' => 1, 'optsItems' => $optsItems]);
