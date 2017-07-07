<div id="tv-wprops-form{$tv|default}"></div>

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
    ,labelAlign: 'top'
    ,cls: 'form-with-labels'
    ,border: false
    ,items: [{
        xtype: 'textfield'
        ,fieldLabel: _('delimiter')
        ,description: MODx.expandHelp ? '' : _('delimiter_desc')
        ,name: 'prop_delimiter'
        ,id: 'prop_delimiter{/literal}{$tv|default}{literal}'
        ,value: params['delimiter'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'prop_delimiter{/literal}{$tv|default}{literal}'
        ,html: _('delimter_desc')
        ,cls: 'desc-under'
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv|default}{literal}'
});
// ]]>
</script>
{/literal}
