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
$forceSelection = $params['forceSelection'] === 'true' || $params['forceSelection'] == 1 ? 'true' : 'false' ;
$typeAhead = $params['typeAhead'] === 'true' || $params['typeAhead'] == 1 ? 'true' : 'false' ;
$typeAheadDelay = !empty($params['typeAheadDelay']) ? $params['typeAheadDelay'] : 250 ;
$listHeader = !empty($params['title']) ? json_encode($params['title']) : 'null' ;
$listEmptyText = !empty($params['listEmptyText']) ? json_encode($params['listEmptyText']) : 'null' ;

# Set help descriptions
$descKeys = [
    'required_desc',
    'combo_listwidth_desc',
    'combo_title_desc',
    'combo_typeahead_desc',
    'combo_typeahead_delay_desc',
    'combo_forceselection_desc',
    'combo_listempty_text_desc'
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
                columnWidth: 0.5,
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
                columnWidth: 0.5,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('combo_title'),
                    description: {$this->helpContent['eh_combo_title_desc']},
                    name: 'inopt_title',
                    id: 'inopt_title{$tvId}',
                    value: {$listHeader}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_title{$tvId}',
                    html: {$this->helpContent['combo_title_desc']},
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
                id: 'tv-input-props-typeahead-enable',
                cls: 'fs-toggle',
                columnWidth: 1,
                defaults: {
                    anchor: '50%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'xcheckbox',
                    hideLabel: true,
                    boxLabel: _('combo_typeahead'),
                    description: {$this->helpContent['eh_combo_typeahead_desc']},
                    name: 'inopt_typeAhead',
                    id: 'inopt_typeAhead{$tvId}',
                    inputValue: 1,
                    checked: {$typeAhead}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_typeAhead{$tvId}',
                    html: {$this->helpContent['combo_typeahead_desc']},
                    cls: 'desc-under toggle-slider-above'
                }]
            }
        ]
    },
    {
        xtype: 'fieldset',
        layout: 'form',
        id: 'tv-input-props-typeahead-options-fs',
        title: 'Type-Ahead Options',
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
                    columnWidth: 0.25,
                    defaults: {
                        anchor: '100%',
                        msgTarget: 'under',
                        hideMode: 'visibility'
                    },
                    items: [{
                        xtype: 'numberfield',
                        fieldLabel: _('combo_typeahead_delay'),
                        description: {$this->helpContent['eh_combo_typeahead_delay_desc']},
                        name: 'inopt_typeAheadDelay',
                        id: 'inopt_typeAheadDelay{$tvId}',
                        allowNegative: false,
                        allowDecimals: false,
                        value: {$typeAheadDelay}
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_typeAheadDelay{$tvId}',
                        html: {$this->helpContent['combo_typeahead_delay_desc']},
                        cls: 'desc-under'
                    }]
                },
                {
                    columnWidth: 0.25,
                    defaults: {
                        anchor: '100%',
                        msgTarget: 'under',
                        hideMode: 'visibility'
                    },
                    items: [{
                        xtype: 'combo-boolean',
                        fieldLabel: _('combo_forceselection'),
                        description: {$this->helpContent['eh_combo_forceselection_desc']},
                        name: 'inopt_forceSelection',
                        hiddenName: 'inopt_forceSelection',
                        id: 'inopt_forceSelection{$tvId}',
                        value: {$forceSelection}
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_forceSelection{$tvId}',
                        html: {$this->helpContent['combo_forceselection_desc']},
                        cls: 'desc-under'
                    }]
                },
                {
                    columnWidth: 0.5,
                    defaults: {
                        anchor: '100%',
                        msgTarget: 'under',
                        hideMode: 'visibility'
                    },
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: _('combo_listempty_text'),
                        description: {$this->helpContent['eh_combo_listempty_text_desc']},
                        name: 'inopt_listEmptyText',
                        id: 'inopt_listEmptyText{$tvId}',
                        value: {$listEmptyText}
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_listEmptyText{$tvId}',
                        html: {$this->helpContent['combo_listempty_text_desc']},
                        cls: 'desc-under'
                    }]
                }
            ]
        }],
        listeners: {
            afterrender: function(cmp) {
                const typeAheadCmp = Ext.getCmp('inopt_typeAhead{$tvId}');
                if (typeAheadCmp) {
                    const   typeAheadOn = typeAheadCmp.getValue(),
                            switchField = 'inopt_typeAhead{$tvId}',
                            toggleFields = [
                                'inopt_typeAheadDelay{$tvId}',
                                'inopt_forceSelection{$tvId}',
                                'inopt_listEmptyText{$tvId}'
                            ]
                            ;
                    typeAheadCmp.on('check', function(){
                        this.toggleFieldVisibility(switchField, cmp.id, toggleFields);
                    }, this);
                    if(!typeAheadOn) {
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
