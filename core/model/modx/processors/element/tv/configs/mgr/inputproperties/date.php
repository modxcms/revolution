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

$hideTime = $params['hideTime'] == "true" || $params['hideTime'] == 1 ? true : false ;

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
    ],
    [
        'xtype' => $helpXtype,
        'forId' => 'inopt_allowBlank'.$tvId,
        'html' => $modx->lexicon('required_desc'),
        'cls' => 'desc-under'
    ],[
        'fieldLabel' => $modx->lexicon('disabled_dates'),
        'description' => $expandHelp ? '' : $modx->lexicon('disabled_dates_desc'),
        'name' => 'inopt_disabledDates',
        'id' => 'inopt_disabledDates'.$tvId,
        'value' => $params['disabledDates'],
        'anchor' => '98%',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_disabledDates'.$tvId,
        'html' => $modx->lexicon('disabled_dates_desc'),
        'cls' => 'desc-under'
    ],[
        'fieldLabel' => $modx->lexicon('disabled_days'),
        'description' => $expandHelp ? '' : $modx->lexicon('disabled_days_desc'),
        'name' => 'inopt_disabledDays',
        'id' => 'inopt_disabledDays'.$tvId,
        'value' => $params['disabledDays'],
        'anchor' => '98%',
        'regex' => '^[0-6]$|^[0-6],[0-6]$|^[0-6](,[0-6]){2,5}$',
        'validationEvent' => 'change',
        'invalidText' => 'Please enter only numbers (0 through 6) separated by commas; no other characters are allowed',
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_disabledDays'.$tvId,
        'html' => $modx->lexicon('disabled_days_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'datefield',
        'fieldLabel' => $modx->lexicon('earliest_date'),
        'description' => $expandHelp ? '' : $modx->lexicon('earliest_date_desc'),
        'name' => 'inopt_minDateValue',
        'id' => 'inopt_minDateValue'.$tvId,
        'value' => $params['minDateValue'],
        'width' => 200,
        'listeners' => 'dirtyOnChange',
        'format' => $modx->getOption('manager_date_format')
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_minDateValue'.$tvId,
        'html' => $modx->lexicon('earliest_date_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'timefield',
        'fieldLabel' => $modx->lexicon('earliest_time'),
        'description' => $expandHelp ? '' : $modx->lexicon('earliest_time_desc'),
        'name' => 'inopt_minTimeValue',
        'id' => 'inopt_minTimeValue'.$tvId,
        'value' => $params['minTimeValue'],
        'width' => 200,
        'listeners' => 'dirtyOnChange',
        'format' => $modx->getOption('manager_time_format')
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_minTimeValue'.$tvId,
        'html' => $modx->lexicon('earliest_time_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'datefield',
        'fieldLabel' => $modx->lexicon('latest_date'),
        'description' => $expandHelp ? '' : $modx->lexicon('latest_date_desc'),
        'name' => 'inopt_maxDateValue',
        'id' => 'inopt_maxDateValue'.$tvId,
        'value' => $params['maxDateValue'],
        'width' => 200,
        'listeners' => 'dirtyOnChange',
        'format' => $modx->getOption('manager_date_format')
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_maxDateValue'.$tvId,
        'html' => $modx->lexicon('latest_date_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'timefield',
        'fieldLabel' => $modx->lexicon('latest_time'),
        'description' => $expandHelp ? '' : $modx->lexicon('latest_time_desc'),
        'name' => 'inopt_maxTimeValue',
        'id' => 'inopt_maxTimeValue'.$tvId,
        'value' => $params['maxTimeValue'],
        'width' => 200,
        'listeners' => 'dirtyOnChange',
        'format' => $modx->getOption('manager_time_format')
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_maxTimeValue'.$tvId,
        'html' => $modx->lexicon('latest_time_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'maxValue' => 6,
        'allowNegative' => false,
        'fieldLabel' => $modx->lexicon('start_day'),
        'description' => $expandHelp ? '' : $modx->lexicon('start_day_desc'),
        'name' => 'inopt_startDay',
        'id' => 'inopt_startDay'.$tvId,
        'value' => $params['startDay'],
        'width' => 100,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_startDay'.$tvId,
        'html' => $modx->lexicon('start_day_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'numberfield',
        'maxValue' => 60,
        'allowNegative' => false,
        'fieldLabel' => $modx->lexicon('time_increment'),
        'description' => $expandHelp ? '' : $modx->lexicon('time_increment_desc'),
        'name' => 'inopt_timeIncrement',
        'id' => 'inopt_timeIncrement'.$tvId,
        'value' => $params['timeIncrement'],
        'width' => 100,
        'listeners' => 'dirtyOnChange'
    ],[
        'xtype' => $helpXtype,
        'forId' => 'inopt_timeIncrement'.$tvId,
        'html' => $modx->lexicon('time_increment_desc'),
        'cls' => 'desc-under'
    ],[
        'xtype' => 'combo-boolean',
        'fieldLabel' => $modx->lexicon('hide_time'),
        'description' => $expandHelp ? '' : $modx->lexicon('hide_time'),
        'name' => 'inopt_hideTime',
        'hiddenName' => 'inopt_hideTime',
        'id' => 'inopt_hideTime'.$tvId,
        'width' => 200,
        'value' => $hideTime,
        'listeners' => 'dirtyOnChange'
    ]
];

return json_encode(['success' => 1, 'optsItems' => $optsItems]);
