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
 * @package MODX\Revolution\Processors\Element\TemplateVar\Configs\mgr\inputproperties
 */

# Set values
$minLength = !empty($params['minLength']) ? $params['minLength'] : 'null' ;
$maxLength = !empty($params['maxLength']) ? $params['maxLength'] : 'null' ;

# Set help descriptions
$descKeys = [
    'required_desc',
    'min_length_desc',
    'max_length_desc'
];
$this->setHelpContent($descKeys, $expandHelp);

$optsJS = <<<OPTSJS
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
                    value: {$allowBlank}
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
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
