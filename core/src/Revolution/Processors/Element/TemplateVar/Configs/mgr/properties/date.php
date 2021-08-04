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
$useDefault = $params['default'] === 'true' || $params['default'] === 1 ? 'true' : 'false' ;
$defaultFormat = "'%A %d, %B %Y'";
$format = !empty($params['format']) ? json_encode($params['format']) : $defaultFormat ;
/*
    The date and string output properties share the same 'format' parameter, which is
    problematic when switching between the two; reset to the default value
    in this case.
*/
$format = strpos($format, '%') === false ? $defaultFormat : $format ;

# Set help descriptions
$descKeys = [
    'date_use_current_desc',
    'date_format_desc'
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
                    fieldLabel: _('date_use_current'),
                    description: {$this->helpContent['eh_date_use_current_desc']},
                    name: 'prop_default',
                    hiddenName: 'prop_default',
                    id: 'prop_default{$tvId}',
                    value: {$useDefault}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_default{$tvId}',
                    html: {$this->helpContent['date_use_current_desc']},
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
                    fieldLabel: _('date_format'),
                    description: {$this->helpContent['eh_date_format_desc']},
                    name: 'prop_format',
                    id: 'prop_format{$tvId}',
                    value: {$format},
                    plugins: new AddFieldUtilities.plugin.Class
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_format{$tvId}',
                    html: {$this->helpContent['date_format_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
