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
 * @package MODX\Revolution\Processors\Element\TemplateVar\Configs\mgr\properties
 */

# Set values
$altText = !empty($params['alttext']) ? json_encode($params['alttext']) : 'null' ;
$id = !empty($params['id']) ? json_encode($params['id']) : 'null' ;
$class = !empty($params['class']) ? json_encode($params['class']) : 'null' ;
$style = !empty($params['style']) ? json_encode($params['style']) : 'null' ;
$attributes = !empty($params['attributes']) ? json_encode($params['attributes']) : 'null' ;

# Set help descriptions
$descKeys = [
    'attr_alt_desc',
    'attr_id_desc',
    'attr_class_desc',
    'attr_style_desc',
    'attr_attr_desc'
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
                columnWidth: 0.33,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('image_alt'),
                    description: {$this->helpContent['eh_attr_alt_desc']},
                    name: 'prop_alttext',
                    id: 'prop_alttext{$tvId}',
                    value: {$altText}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_alttext{$tvId}',
                    html: {$this->helpContent['attr_alt_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.33,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('id'),
                    description: {$this->helpContent['eh_attr_id_desc']},
                    name: 'prop_id',
                    id: 'prop_id{$tvId}',
                    value: {$id}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_id{$tvId}',
                    html: {$this->helpContent['attr_id_desc']},
                    cls: 'desc-under'
                }]
            },{
                xtype: 'panel',
                columnWidth: 0.34,
                autoHeight: true,
                labelAlign: 'top',
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('class'),
                    description: {$this->helpContent['eh_attr_class_desc']},
                    name: 'prop_class',
                    id: 'prop_class{$tvId}',
                    value: {$class}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_class{$tvId}',
                    html: {$this->helpContent['attr_class_desc']},
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
                    fieldLabel: _('style'),
                    description: {$this->helpContent['eh_attr_style_desc']},
                    name: 'prop_style',
                    id: 'prop_style{$tvId}',
                    value: {$style},
                    plugins: new AddFieldUtilities.plugin.Class
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_style{$tvId}',
                    html: {$this->helpContent['attr_style_desc']},
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
                    fieldLabel: _('attributes'),
                    description: {$this->helpContent['eh_attr_attr_desc']},
                    name: 'prop_attributes',
                    id: 'prop_attributes{$tvId}',
                    value: {$attributes},
                    plugins: new AddFieldUtilities.plugin.Class
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_attributes{$tvId}',
                    html: {$this->helpContent['attr_attr_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
