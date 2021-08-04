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
$width = !empty($params['w']) ? json_encode($params['w']) : "'100%'" ;
$height = !empty($params['h']) ? json_encode($params['h']) : "'300px'" ;

# Set help descriptions
$descKeys = [
    'rte_width_desc',
    'rte_height_desc'
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
                    xtype: 'textfield',
                    fieldLabel: _('width'),
                    description: {$this->helpContent['eh_rte_width_desc']},
                    name: 'prop_w',
                    id: 'prop_w{$tvId}',
                    value: {$width}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_w{$tvId}',
                    html: {$this->helpContent['rte_width_desc']},
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
                    fieldLabel: _('height'),
                    description: {$this->helpContent['eh_rte_height_desc']},
                    name: 'prop_h',
                    id: 'prop_h{$tvId}',
                    value: {$height}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_h{$tvId}',
                    html: {$this->helpContent['rte_height_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
