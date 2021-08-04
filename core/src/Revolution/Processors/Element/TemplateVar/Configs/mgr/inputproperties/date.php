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
$startDay = (int)$params['startDay'] >= 1 ? $params['startDay'] : 0 ;
$disabledDays = !empty($params['disabledDays']) ? json_encode($params['disabledDays']) : 'null' ;
$minDateValue = !empty($params['minDateValue']) ? json_encode($params['minDateValue']) : 'null' ;
$maxDateValue = !empty($params['maxDateValue']) ? json_encode($params['maxDateValue']) : 'null' ;
$disabledDates = !empty($params['disabledDates']) ? json_encode($params['disabledDates']) : 'null' ;

$hideTime = $params['hideTime'] === 'true' || $params['hideTime'] == 1 ? 'true' : 'false' ;
$minTimeValue = !empty($params['minTimeValue']) ? json_encode($params['minTimeValue']) : 'null' ;
$maxTimeValue = !empty($params['maxTimeValue']) ? json_encode($params['maxTimeValue']) : 'null' ;
$timeIncrement = !empty($params['timeIncrement']) ? $params['timeIncrement'] : 15 ;

# Set help descriptions
$descKeys = [
    'required_desc',
    'start_day_desc',
    'disabled_days_desc',
    'earliest_date_desc',
    'latest_date_desc',
    'disabled_dates_desc',
    'hide_time_desc',
    'earliest_time_desc',
    'latest_time_desc',
    'time_increment_desc'
];
$this->setHelpContent($descKeys, $expandHelp);

$optsJS = <<<OPTSJS
[
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
                columnWidth: 0.25,
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
            },
            {
                columnWidth: 0.25,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo',
                    fieldLabel: _('start_day'),
                    description: {$this->helpContent['eh_start_day_desc']},
                    name: 'inopt_startDay',
                    hiddenName: 'inopt_startDay',
                    id: 'inopt_startDay{$tvId}',
                    store: new Ext.data.SimpleStore({
                        fields: ['v','d']
                        ,data: [
                            [0,_('sunday')],
                            [1,_('monday')],
                            [2,_('tuesday')],
                            [3,_('wednesday')],
                            [4,_('thursday')],
                            [5,_('friday')],
                            [6,_('saturday')]
                        ]
                    }),
                    displayField: 'd',
                    valueField: 'v',
                    mode: 'local',
                    editable: false,
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    value: {$startDay}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_startDay{$tvId}',
                    html: {$this->helpContent['start_day_desc']},
                    cls: 'desc-under'
                }]
            },
            {
                columnWidth: 0.5,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'xcheckboxgroup',
                    id: 'inopt_disabledDays{$tvId}',
                    name: 'inopt_disabledDays',
                    fieldLabel: _('disabled_days'),
                    columns: 3,
                    value: {$disabledDays},
                    items: [{
                        boxLabel: _('saturday'),
                        inputValue: 6
                    },{
                        boxLabel: _('sunday'),
                        inputValue: 0
                    },{
                        boxLabel: _('monday'),
                        inputValue: 1
                    },{
                        boxLabel: _('tuesday'),
                        inputValue: 2
                    },{
                        boxLabel: _('wednesday'),
                        inputValue: 3
                    },{
                        boxLabel: _('thursday'),
                        inputValue: 4
                    },{
                        boxLabel: _('friday'),
                        inputValue: 5
                    }],
                    plugins: new AddFieldUtilities.plugin.Class
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_disabledDays{$tvId}',
                    html: {$this->helpContent['disabled_days_desc']},
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
                columnWidth: 1,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('disabled_dates'),
                    description: {$this->helpContent['eh_disabled_dates_desc']},
                    name: 'inopt_disabledDates',
                    id: 'inopt_disabledDates{$tvId}',
                    value: {$disabledDates},
                    plugins: new AddFieldUtilities.plugin.Class
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_disabledDates{$tvId}',
                    html: {$this->helpContent['disabled_dates_desc']},
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
                columnWidth: 0.5,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'datefield',
                    fieldLabel: _('earliest_date'),
                    description: {$this->helpContent['eh_earliest_date_desc']},
                    name: 'inopt_minDateValue',
                    id: 'inopt_minDateValue{$tvId}',
                    format: '{$modx->getOption('manager_date_format')}',
                    value: {$minDateValue}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_minDateValue{$tvId}',
                    html: {$this->helpContent['earliest_date_desc']},
                    cls: 'desc-under'
                }]
            },
            {
                columnWidth: 0.5,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'datefield',
                    fieldLabel: _('latest_date'),
                    description: {$this->helpContent['eh_latest_date_desc']},
                    name: 'inopt_maxDateValue',
                    id: 'inopt_maxDateValue{$tvId}',
                    format: '{$modx->getOption('manager_date_format')}',
                    value: {$maxDateValue}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_maxDateValue{$tvId}',
                    html: {$this->helpContent['latest_date_desc']},
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
                cls: 'fs-toggle',
                columnWidth: 1,
                defaults: {
                    anchor: '50%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'xcheckbox',
                    hideLabel: true,
                    boxLabel: _('hide_time'),
                    description: {$this->helpContent['eh_hide_time_desc']},
                    name: 'inopt_hideTime',
                    id: 'inopt_hideTime{$tvId}',
                    inputValue: 1,
                    checked: {$hideTime}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_hideTime{$tvId}',
                    html: {$this->helpContent['hide_time_desc']},
                    cls: 'desc-under toggle-slider-above'
                }]
            }
        ]
    },
    {
        xtype: 'fieldset',
        layout: 'form',
        id: 'tv-input-props-time-options-fs',
        title: 'Time Options',
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
                        xtype: 'timefield',
                        fieldLabel: _('earliest_time'),
                        description: {$this->helpContent['eh_earliest_time_desc']},
                        name: 'inopt_minTimeValue',
                        id: 'inopt_minTimeValue{$tvId}',
                        format: '{$modx->getOption('manager_time_format')}',
                        value: {$minTimeValue}
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_minTimeValue{$tvId}',
                        html: {$this->helpContent['earliest_time_desc']},
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
                        xtype: 'timefield',
                        fieldLabel: _('latest_time'),
                        description: {$this->helpContent['eh_latest_time_desc']},
                        name: 'inopt_maxTimeValue',
                        id: 'inopt_maxTimeValue{$tvId}',
                        format: '{$modx->getOption('manager_time_format')}',
                        value: {$maxTimeValue}
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_maxTimeValue{$tvId}',
                        html: {$this->helpContent['latest_time_desc']},
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
                        xtype: 'numberfield',
                        fieldLabel: _('time_increment'),
                        description: {$this->helpContent['eh_time_increment_desc']},
                        name: 'inopt_timeIncrement',
                        id: 'inopt_timeIncrement{$tvId}',
                        maxValue: 60,
                        allowNegative: false,
                        value: {$timeIncrement}
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_timeIncrement{$tvId}',
                        html: {$this->helpContent['time_increment_desc']},
                        cls: 'desc-under'
                    }]
                }
            ]
        }],
        listeners: {
            afterrender: function(cmp) {
                const hideTimeCmp = Ext.getCmp('inopt_hideTime{$tvId}');
                if (hideTimeCmp) {
                    const   hideTimeOn = hideTimeCmp.getValue(),
                            switchField = 'inopt_hideTime{$tvId}',
                            toggleFields = [
                                'inopt_minTimeValue{$tvId}',
                                'inopt_maxTimeValue{$tvId}',
                                'inopt_timeIncrement{$tvId}'
                            ]
                        ;
                    hideTimeCmp.on('check', function(){
                        this.toggleFieldVisibility(switchField, cmp.id, toggleFields, false);
                    }, this);
                    if(hideTimeOn) {
                        this.toggleFieldVisibility(switchField, cmp.id, toggleFields, false);
                    }
                }
            },
            scope: Ext.getCmp('modx-panel-tv')
        }
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
