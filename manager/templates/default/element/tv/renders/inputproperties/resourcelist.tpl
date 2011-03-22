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
    ,labelWidth: 150
    ,border: false
    ,items: [{
        xtype: 'combo-boolean'
        ,fieldLabel: _('required')
        ,description: _('required_desc')
        ,name: 'inopt_allowBlank'
        ,hiddenName: 'inopt_allowBlank'
        ,id: 'inopt_allowBlank{/literal}{$tv}{literal}'
        ,value: params['allowBlank'] == 0 || params['allowBlank'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('shownone')
        ,description: _('shownone_desc')
        ,name: 'inopt_showNone'
        ,hiddenName: 'inopt_showNone'
        ,id: 'inopt_showNone{/literal}{$tv}{literal}'
        ,value: params['showNone'] == 0 || params['showNone'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('resourcelist_parents')
        ,description: _('resourcelist_parents_desc')
        ,name: 'inopt_parents'
        ,id: 'inopt_parents{/literal}{$tv}{literal}'
        ,value: params['parents'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('resourcelist_depth')
        ,description: _('resourcelist_depth_desc')
        ,name: 'inopt_depth'
        ,id: 'inopt_depth{/literal}{$tv}{literal}'
        ,value: params['depth'] || 10
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('resourcelist_includeparent')
        ,description: _('resourcelist_includeparent_desc')
        ,name: 'inopt_includeParent'
        ,hiddenName: 'inopt_includeParent'
        ,id: 'inopt_includeParent{/literal}{$tv}{literal}'
        ,value: params['includeParent'] == 'false' || Ext.isEmpty(params['includeParent']) ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('resourcelist_where')
        ,description: _('resourcelist_where_desc')
        ,name: 'inopt_where'
        ,id: 'inopt_where{/literal}{$tv}{literal}'
        ,value: params['where'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('resourcelist_limit')
        ,description: _('resourcelist_limit_desc')
        ,name: 'inopt_limit'
        ,id: 'inopt_limit{/literal}{$tv}{literal}'
        ,value: params['limit'] || 0
        ,allowNegative: false
        ,allowDecimals: false
        ,width: 300
        ,listeners: oc
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}