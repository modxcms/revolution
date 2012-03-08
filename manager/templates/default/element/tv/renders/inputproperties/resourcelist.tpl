<div id="tv-input-properties-form{$tv}"></div>
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
    ,autoHeight: true
    ,cls: 'form-with-labels'
    ,labelAlign: 'top'
    ,border: false
    ,items: [{
        xtype: 'combo-boolean'
        ,fieldLabel: _('required')
        ,description: MODx.expandHelp ? '' : _('required_desc')
        ,name: 'inopt_allowBlank'
        ,hiddenName: 'inopt_allowBlank'
        ,id: 'inopt_allowBlank{/literal}{$tv}{literal}'
        ,value: params['allowBlank'] == 0 || params['allowBlank'] == 'false' ? 0 : 1
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowBlank{/literal}{$tv}{literal}'
        ,html: _('required_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('shownone')
        ,description: MODx.expandHelp ? '' : _('shownone_desc')
        ,name: 'inopt_showNone'
        ,hiddenName: 'inopt_showNone'
        ,id: 'inopt_showNone{/literal}{$tv}{literal}'
        ,value: params['showNone'] == 0 || params['showNone'] == 'false' ? 0 : 1
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_showNone{/literal}{$tv}{literal}'
        ,html: _('shownone_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('resourcelist_parents')
        ,description: MODx.expandHelp ? '' : _('resourcelist_parents_desc')
        ,name: 'inopt_parents'
        ,id: 'inopt_parents{/literal}{$tv}{literal}'
        ,value: params['parents'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_parents{/literal}{$tv}{literal}'
        ,html: _('resourcelist_parents_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('resourcelist_depth')
        ,description: MODx.expandHelp ? '' : _('resourcelist_depth_desc')
        ,name: 'inopt_depth'
        ,id: 'inopt_depth{/literal}{$tv}{literal}'
        ,value: params['depth'] || 10
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_depth{/literal}{$tv}{literal}'
        ,html: _('resourcelist_depth_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('resourcelist_includeparent')
        ,description: MODx.expandHelp ? '' : _('resourcelist_includeparent_desc')
        ,name: 'inopt_includeParent'
        ,hiddenName: 'inopt_includeParent'
        ,id: 'inopt_includeParent{/literal}{$tv}{literal}'
        ,value: params['includeParent'] == 0 || params['includeParent'] == 'false' ? 0 : 1
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_includeParent{/literal}{$tv}{literal}'
        ,html: _('resourcelist_includeparent_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('resourcelist_limitrelatedcontext')
        ,description: MODx.expandHelp ? '' : _('resourcelist_limitrelatedcontext_desc')
        ,name: 'inopt_limitRelatedContext'
        ,hiddenName: 'inopt_limitRelatedContext'
        ,id: 'inopt_limitRelatedContext{/literal}{$tv}{literal}'
        ,value: params['limitRelatedContext'] == 1 || params['limitRelatedContext'] == 'true' ? 1 : 0
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_limitRelatedContext{/literal}{$tv}{literal}'
        ,html: _('resourcelist_limitrelatedcontext_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textarea'
        ,fieldLabel: _('resourcelist_where')
        ,description: MODx.expandHelp ? '' : _('resourcelist_where_desc')
        ,name: 'inopt_where'
        ,id: 'inopt_where{/literal}{$tv}{literal}'
        ,value: params['where'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_where{/literal}{$tv}{literal}'
        ,html: _('resourcelist_where_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('resourcelist_limit')
        ,description: MODx.expandHelp ? '' : _('resourcelist_limit_desc')
        ,name: 'inopt_limit'
        ,id: 'inopt_limit{/literal}{$tv}{literal}'
        ,value: params['limit'] || 0
        ,allowNegative: false
        ,allowDecimals: false
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_limit{/literal}{$tv}{literal}'
        ,html: _('resourcelist_limit_desc')
        ,cls: 'desc-under'
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}
