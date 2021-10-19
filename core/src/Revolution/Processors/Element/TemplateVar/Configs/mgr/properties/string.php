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
$format = !empty($params['format']) ? json_encode($params['format']) : "''" ;
/*
    The date and string output properties share the same 'format' parameter, which is
    problematic when switching between the two; reset to the default value
    in this case.
*/
$format = strpos($format, '%') !== false ? "''" : $format ;

# Set help descriptions
$descKeys = [
    'string_format_desc'
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
                    xtype: 'combo',
                    fieldLabel: _('string_format'),
                    description: {$this->helpContent['eh_string_format_desc']},
                    name: 'prop_format',
                    hiddenName: 'prop_format',
                    id: 'prop_format{$tvId}',
                    store: new Ext.data.SimpleStore({
                        fields: ['v','d']
                        ,data: [
                            ['',_('none')],
                            ['Upper Case',_('upper_case')],
                            ['Lower Case',_('lower_case')],
                            ['Sentence Case',_('sentence_case')],
                            ['Capitalize',_('capitalize')]
                        ]
                    }),
                    displayField: 'd',
                    valueField: 'v',
                    mode: 'local',
                    editable: false,
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    value: {$format}
                },{
                    xtype: '{$helpXtype}',
                    forId: 'prop_format{$tvId}',
                    html: {$this->helpContent['string_format_desc']},
                    cls: 'desc-under'
                }]
            }
        ]
    }
]
OPTSJS;

return "{'success': 1, 'optsItems': $optsJS}";
