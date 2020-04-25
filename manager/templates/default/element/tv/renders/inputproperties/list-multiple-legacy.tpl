<div id="tv-input-properties-form{$tv|default}"></div>
{literal}

<script type="text/javascript">
// <![CDATA[
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};
MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,cls: 'form-with-labels'
    ,labelAlign: 'top'
    ,autoHeight: true
    ,border: false
    ,items: [{
        xtype: 'combo-boolean'
        ,fieldLabel: _('required')
        ,description: _('required_desc')
        ,name: 'inopt_allowBlank'
        ,hiddenName: 'inopt_allowBlank'
        ,id: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,anchor: '100%'
        ,value: (params['allowBlank']) ? !(params['allowBlank'] === 0 || params['allowBlank'] === 'false') : true
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,html: _('required_desc')
        ,cls: 'desc-under'
    },{
        layout: 'column'
        ,border: false
        ,defaults: {
            layout: 'form'
            ,labelAlign: 'top'
            ,labelSeparator: ''
            ,anchor: '100%'
            ,border: false
        }
        ,items: [{
            columnWidth: .5
            ,items: [{
                xtype: 'textfield'
                ,fieldLabel: _('combo_listwidth')
                ,description: MODx.expandHelp ? '' : _('combo_listwidth_desc')
                ,name: 'inopt_listWidth'
                ,id: 'inopt_listWidth{/literal}{$tv|default}{literal}'
                ,value: params['listWidth'] || ''
                ,anchor: '100%'
                ,listeners: oc
            },{
                xtype: MODx.expandHelp ? 'label' : 'hidden'
                ,forId: 'inopt_listWidth{/literal}{$tv|default}{literal}'
                ,html: _('combo_listwidth_desc')
                ,cls: 'desc-under'
            }]
        },{
            columnWidth: .5
            ,items: [{
                xtype: 'textfield'
                ,fieldLabel: _('combo_listheight')
                ,description: MODx.expandHelp ? '' : _('combo_listheight_desc')
                ,name: 'inopt_listHeight'
                ,id: 'inopt_listHeight{/literal}{$tv|default}{literal}'
                ,value: params['listHeight'] || ''
                ,anchor: '100%'
                ,listeners: oc
            },{
                xtype: MODx.expandHelp ? 'label' : 'hidden'
                ,forId: 'inopt_listHeight{/literal}{$tv|default}{literal}'
                ,html: _('combo_listheight_desc')
                ,cls: 'desc-under'
            }]
        }]
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv|default}{literal}'
});
// ]]>
</script>
{/literal}
