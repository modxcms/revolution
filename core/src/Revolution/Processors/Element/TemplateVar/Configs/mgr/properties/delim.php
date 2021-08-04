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
$delimiter = !empty($params['delimiter']) ? json_encode($params['delimiter']) : 'null' ;

# Set help descriptions
$descKeys = [
    'delimiter_desc'
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
                    xtype: 'textfield',
                    fieldLabel: _('delimiter'),
                    description: {$this->helpContent['eh_delimiter_desc']},
                    name: 'prop_delimiter',
                    id: 'prop_delimiter{$tvId}',
                    value: {$delimiter}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_delimiter{$tvId}',
                    html: {$this->helpContent['delimiter_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
