<div id="modx-tv-wprops-form{$tv|default}"></div>
{literal}

<script>
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
    ,autoHeight: true
    ,labelAlign: 'top'
    ,cls: 'form-with-labels'
    ,border: false
    ,items: [{
        xtype: 'textfield'
        ,fieldLabel: _('date_format')
        ,description: MODx.expandHelp ? '' : _('date_format_desc')
        ,name: 'prop_format'
        ,id: 'prop_format{/literal}{$tv|default}{literal}'
        ,value: params['format'] || '%A %d, %B %Y'
        ,listeners: oc
        ,anchor: '100%'
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'prop_format{/literal}{$tv|default}{literal}'
        ,html: _('date_format_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'combo'
        ,fieldLabel: _('date_use_current')
        ,description: MODx.expandHelp ? '' : _('date_use_current_desc')
        ,name: 'prop_default'
        ,hiddenName: 'prop_default'
        ,id: 'prop_default{/literal}{$tv|default}{literal}'
        ,store: new Ext.data.SimpleStore({
            fields: ['v','d']
            ,data: [[1,_('yes')],[0,_('no')]]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,editable: false
        ,forceSelection: true
        ,typeAhead: false
        ,triggerAction: 'all'
        ,value: params['default'] || 'no'
        ,listeners: oc
        ,width: 200
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'prop_default{/literal}{$tv|default}{literal}'
        ,html: _('default_desc')
        ,cls: 'desc-under'
    }]
    ,renderTo: 'modx-tv-wprops-form{/literal}{$tv|default}{literal}'
});
// ]]>
</script>
{/literal}
