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

/*
    NOTE re decimalSeparator - The original description (taken directly from the extjs docs)
    is misleading, in that the value for the property decimalSeparator not only indicates
    the characters *allowed*, but also *is* the separator (i.e., if you enter ".,", your numbers
    will be separated by that pair of characters ... even if you key in "1.2", the value saved will
    be "1.,2"). As such, now limiting the maxLength to 1 and updated lexicon desc to be more accurate.
*/

# Set values
$allowDecimals = $params['allowDecimals'] === 'true' || $params['allowDecimals'] == 1 ? 'true' : 'false' ;
$strictDecimalPrecision = $params['strictDecimalPrecision'] === 'true' || $params['strictDecimalPrecision'] == 1 ? 'true' : 'false' ;
$decimalPrecision = !empty($params['decimalPrecision']) ? $params['decimalPrecision'] : 2 ;
$decimalSeparator = !empty($params['decimalSeparator'])
    ? json_encode($params['decimalSeparator'])
    : json_encode('.')
    ;
$minValue = !empty($params['minValue']) || $params['minValue'] === '0'
    ? $params['minValue']
    : 'null'
    ;
$maxValue = !empty($params['maxValue']) || $params['maxValue'] === '0'
    ? $params['maxValue']
    : 'null'
    ;

# Set help descriptions
$descKeys = [
    'required_desc',
    'allowdecimals_desc',
    'allownegative_desc',
    'number_decimalprecision_desc',
    'number_decimalprecision_strict_desc',
    'number_decimalseparator_desc',
    'minvalue_desc',
    'maxvalue_desc'
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
                columnWidth: 0.5,
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
                    fieldLabel: _('number_minvalue'),
                    description: {$this->helpContent['eh_minvalue_desc']},
                    name: 'inopt_minValue',
                    id: 'inopt_minValue{$tvId}',
                    value: {$minValue},
                    validator: 'minLtMax'
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_minValue{$tvId}',
                    html: {$this->helpContent['minvalue_desc']},
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
                    fieldLabel: _('number_maxvalue'),
                    description: {$this->helpContent['eh_maxvalue_desc']},
                    name: 'inopt_maxValue',
                    id: 'inopt_maxValue{$tvId}',
                    value: {$maxValue},
                    validator: 'maxGtMin'
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_maxValue{$tvId}',
                    html: {$this->helpContent['maxvalue_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    },
    {
        defaults: {
            xtype: 'panel',
            layout: 'form',
            labelAlign: 'top',
            autoHeight: true,
            labelSeparator: ''
        },
        items: [
            {
                id: 'tv-input-props-decimalopts-show',
                cls: 'fs-toggle',
                columnWidth: 1,
                defaults: {
                    anchor: '40%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'xcheckbox',
                    hideLabel: true,
                    boxLabel: _('number_allowdecimals'),
                    description: {$this->helpContent['eh_allowdecimals_desc']},
                    name: 'inopt_allowDecimals',
                    id: 'inopt_allowDecimals{$tvId}',
                    inputValue: 1,
                    checked: {$allowDecimals}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_allowDecimals{$tvId}',
                    html: {$this->helpContent['allowdecimals_desc']},
                    cls: 'desc-under toggle-slider-above'
                }]
            }
        ]
    },
    {
        xtype: 'fieldset',
        layout: 'form',
        id: 'tv-input-props-decimalopts-fs',
        title: 'Decimal Options',
        autoHeight: true,
        columnWidth: 1,
        items: [{
            layout: 'column',
            defaults: {
                xtype: 'panel',
                layout: 'form',
                labelAlign: 'top',
                autoHeight: true,
                labelSeparator: ''
            },
            items: [
                {
                    columnWidth: 0.33,
                    defaults: {
                        anchor: '100%',
                        msgTarget: 'under',
                        hideMode: 'visibility'
                    },
                    items: [{
                        xtype: 'numberfield',
                        fieldLabel: _('number_decimalprecision'),
                        description: {$this->helpContent['eh_number_decimalprecision_desc']},
                        allowNegative: false,
                        allowDecimals: false,
                        name: 'inopt_decimalPrecision',
                        id: 'inopt_decimalPrecision{$tvId}',
                        value: {$decimalPrecision}
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_decimalPrecision{$tvId}',
                        html: {$this->helpContent['number_decimalprecision_desc']},
                        cls: 'desc-under'
                    }]
                },
                {
                    columnWidth: 0.33,
                    defaults: {
                        anchor: '100%',
                        msgTarget: 'under',
                        hideMode: 'visibility'
                    },
                    items: [{
                        xtype: 'combo-boolean',
                        fieldLabel: _('number_decimalprecision_strict'),
                        description: {$this->helpContent['eh_number_decimalprecision_strict_desc']},
                        name: 'inopt_strictDecimalPrecision',
                        hiddenName: 'inopt_strictDecimalPrecision',
                        id: 'inopt_strictDecimalPrecision{$tvId}',
                        value: {$strictDecimalPrecision}
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_strictDecimalPrecision{$tvId}',
                        html: {$this->helpContent['number_decimalprecision_strict_desc']},
                        cls: 'desc-under'
                    }]
                },
                {
                    columnWidth: 0.34,
                    defaults: {
                        anchor: '100%',
                        msgTarget: 'under',
                        hideMode: 'visibility'
                    },
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: _('number_decimalseparator'),
                        description: {$this->helpContent['eh_number_decimalseparator_desc']},
                        name: 'inopt_decimalSeparator',
                        id: 'inopt_decimalSeparator{$tvId}',
                        maxLength: 1,
                        value: {$decimalSeparator}
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_decimalSeparator{$tvId}',
                        html: {$this->helpContent['number_decimalseparator_desc']},
                        cls: 'desc-under'
                    }]
                }
            ]
        }],
        listeners: {
            afterrender: function(cmp) {
                const allowDecimalsCmp = Ext.getCmp('inopt_allowDecimals{$tvId}');
                if (allowDecimalsCmp) {
                    const   showDecimalsOn = allowDecimalsCmp.getValue(),
                            switchField = 'inopt_allowDecimals{$tvId}',
                            toggleFields = [
                                'inopt_decimalPrecision{$tvId}',
                                'inopt_decimalSeparator{$tvId}'
                            ]
                            ;
                    allowDecimalsCmp.on('check', function(){
                        this.toggleFieldVisibility(switchField, cmp.id, toggleFields);
                    }, this);
                    if(!showDecimalsOn) {
                        this.toggleFieldVisibility(switchField, cmp.id, toggleFields);
                    }
                }
            },
            scope: Ext.getCmp('modx-panel-tv')
        }
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
