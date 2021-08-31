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
$showNone = $params['showNone'] === 'false' || $params['showNone'] == 0 ? 'false' : 'true' ;
$includeParent = $params['includeParent'] === 'false' || $params['includeParent'] == 0 ? 'false' : 'true' ;
$limitRelatedContext =
    $params['limitRelatedContext'] == "true" || $params['limitRelatedContext'] == 1
    ? 'true'
    : 'false'
    ;
$depth = $params['depth'] === '0' ? 0 : $params['depth'] ;
$depth = $depth === 0 || $depth >= 1 ? $depth : 10 ;
$limit = $params['limit'] >= 1 ? $params['limit'] : 0 ;
$parents = !empty($params['parents']) ? json_encode($params['parents']) : 'null' ;
$where = !empty($params['where']) ? json_encode($params['where']) : 'null' ;
$typeAhead = $params['typeAhead'] === 'true' || $params['typeAhead'] == 1 ? 'true' : 'false' ;
$typeAheadDelay = !empty($params['typeAheadDelay']) ? $params['typeAheadDelay'] : 250 ;

# Set help descriptions
$descKeys = [
    'required_desc',
    'resourcelist_parents_desc',
    'resourcelist_depth_desc',
    'resourcelist_includeparent_desc',
    'resourcelist_limitrelatedcontext_desc',
    'resourcelist_where_desc',
    'resourcelist_limit_desc',
    'combo_typeahead_desc',
    'combo_typeahead_delay_desc',
    'resourcelist_forceselection_desc',
    'resourcelist_listempty_text_desc'
];
$this->setHelpContent($descKeys, $expandHelp);

$optsJSON = <<<OPTSJS
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
                    xtype: 'textfield',
                    fieldLabel: _('resourcelist_parents'),
                    description: {$this->helpContent['eh_resourcelist_parents_desc']},
                    name: 'inopt_parents',
                    id: 'inopt_parents{$tvId}',
                    maskRe: '[0-9,]',
                    value: {$parents}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_parents{$tvId}',
                    html: {$this->helpContent['resourcelist_parents_desc']},
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
                    xtype: 'combo-boolean',
                    fieldLabel: _('resourcelist_includeparent'),
                    description: {$this->helpContent['eh_resourcelist_includeparent_desc']},
                    name: 'inopt_includeParent',
                    hiddenName: 'inopt_includeParent',
                    id: 'inopt_includeParent{$tvId}',
                    value: {$includeParent}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_includeParent{$tvId}',
                    html: {$this->helpContent['resourcelist_includeparent_desc']},
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
                columnWidth: 0.333,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'numberfield',
                    fieldLabel: _('resourcelist_limit'),
                    description: {$this->helpContent['eh_resourcelist_limit_desc']},
                    name: 'inopt_limit',
                    id: 'inopt_limit{$tvId}',
                    allowNegative: false,
                    allowDecimals: false,
                    value: {$limit}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_limit{$tvId}',
                    html: {$this->helpContent['resourcelist_limit_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.333,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('resourcelist_limitrelatedcontext'),
                    description: {$this->helpContent['eh_resourcelist_limitrelatedcontext_desc']},
                    name: 'inopt_limitRelatedContext',
                    hiddenName: 'inopt_limitRelatedContext',
                    id: 'inopt_limitRelatedContext{$tvId}',
                    value: {$limitRelatedContext}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_limitRelatedContext{$tvId}',
                    html: {$this->helpContent['resourcelist_limitrelatedcontext_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.334,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'numberfield',
                    fieldLabel: _('resourcelist_depth'),
                    description: {$this->helpContent['eh_resourcelist_depth_desc']},
                    name: 'inopt_depth',
                    id: 'inopt_depth{$tvId}',
                    allowNegative: false,
                    allowDecimals: false,
                    value: {$depth}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_depth{$tvId}',
                    html: {$this->helpContent['resourcelist_depth_desc']},
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
                columnWidth: 1,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textarea',
                    fieldLabel: _('resourcelist_where'),
                    description: {$this->helpContent['eh_resourcelist_where_desc']},
                    name: 'inopt_where',
                    id: 'inopt_where{$tvId}',
                    value: {$where},
                    plugins: new AddFieldUtilities.plugin.Class
                },{
                    xtype: '{$helpXtype}',
                    forId: 'inopt_where{$tvId}',
                    html: {$this->helpContent['resourcelist_where_desc']},
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
                    anchor: '40%',
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
                        description: {$this->helpContent['eh_resourcelist_forceselection_desc']},
                        itemCls: 'disabled',
                        name: 'inopt_forceSelection',
                        hiddenName: 'inopt_forceSelection',
                        id: 'inopt_forceSelection{$tvId}',
                        disabled: true,
                        value: true
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_forceSelection{$tvId}',
                        html: {$this->helpContent['resourcelist_forceselection_desc']},
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
                        description: {$this->helpContent['eh_resourcelist_listempty_text_desc']},
                        itemCls: 'disabled',
                        name: 'inopt_listEmptyText',
                        id: 'inopt_listEmptyText{$tvId}',
                        disabled: true,
                        value: ''
                    },{
                        xtype: '{$helpXtype}',
                        forId: 'inopt_listEmptyText{$tvId}',
                        html: {$this->helpContent['resourcelist_listempty_text_desc']},
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

return "{'success': 1, 'optsItems': $optsJSON}";
