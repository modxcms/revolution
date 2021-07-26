<?php
/*
 * This file is part of a proposed change to MODX Revolution's tv input option rendering in the back end.
 * Developed by Jim Graham (smg6511), Pixels & Strings, LLC (formerly Spark Media Group)
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @package MODX\Revolution\Processors\Element\TemplateVar\Configs\mgr\inputproperties
 */

# Set values
$minLength = !empty($params['minLength']) ? $params['minLength'] : 'null' ;
$maxLength = !empty($params['maxLength']) ? $params['maxLength'] : 'null' ;
$regex = !empty($params['regex']) ? json_encode($params['regex']) : 'null' ;
$regexText = !empty($params['regexText']) ? json_encode($params['regexText']) : 'null' ;

# Set help descriptions
$descKeys = [
    'required_desc',
    'min_length_desc',
    'max_length_desc',
    'regex_desc',
    'regex_text_desc'
];
$this->setHelpContent($descKeys, $expandHelp);

$optsJSON = <<<OPTSJSON
[
    {
        defaults: {
            layout: 'form',
            labelSeparator: ''
        },
        items: [
            {
                xtype: 'panel',
                columnWidth: 1,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('required'),
                    description: {$this->helpContent['eh_required_desc']},
                    name: 'inopt_allowBlank',
                    hiddenName: 'inopt_allowBlank',
                    id: 'inopt_allowBlank{$tvId}',
                    tabIndex: 1,
                    value: {$allowBlank},
                    listeners: 'testComboEvt'
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_allowBlank{$tvId}',
                    html: {$this->helpContent['required_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    },
    {
        defaults: {
            layout: 'form',
            labelSeparator: ''
        },
        items: [
            {
                xtype: 'panel',
                columnWidth: 0.5,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'numberfield',
                    fieldLabel: _('min_length'),
                    description: {$this->helpContent['eh_min_length_desc']},
                    name: 'inopt_minLength',
                    id: 'inopt_minLength{$tvId}',
                    tabIndex: 2,
                    allowDecimals: false,
                    allowNegative: false,
                    value: {$minLength},
                    validator: 'minLtMax'
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_minLength{$tvId}',
                    html: {$this->helpContent['min_length_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.5,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'numberfield',
                    fieldLabel: _('max_length'),
                    description: {$this->helpContent['eh_max_length_desc']},
                    name: 'inopt_maxLength',
                    id: 'inopt_maxLength{$tvId}',
                    tabIndex: 3,
                    allowDecimals: false,
                    allowNegative: false,
                    value: {$maxLength},
                    validator: 'maxGtMin'
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_maxLength{$tvId}',
                    html: {$this->helpContent['max_length_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    },
    {
        defaults: {
            layout: 'form',
            labelSeparator: ''
        },
        items: [
            {
                xtype: 'panel',
                columnWidth: 0.5,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('regex'),
                    description: {$this->helpContent['eh_regex_desc']},
                    name: 'inopt_regex',
                    id: 'inopt_regex{$tvId}',
                    tabIndex: 4,
                    value: {$regex},
                    plugins: new AddFieldUtilities.plugin.Class,
                    listeners: 'testInputEvt,testDirtyOnBlur'
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_regex{$tvId}',
                    html: {$this->helpContent['regex_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.5,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('regex_text'),
                    description: {$this->helpContent['eh_regex_text_desc']},
                    name: 'inopt_regexText',
                    id: 'inopt_regexText{$tvId}',
                    tabIndex: 5,
                    value: {$regexText},
                    listeners: 'testInputEvt'
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_regexText{$tvId}',
                    html: {$this->helpContent['regex_text_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    }
]
OPTSJSON;

return "{'success': 1, 'optsItems': $optsJSON}";
