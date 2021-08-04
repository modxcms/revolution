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

# Set help descriptions
$descKeys = ['required_desc'];
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
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
