<div id="tv-input-properties-form{$tv|default}"></div>

{literal}
<script type="text/javascript">
// <![CDATA[
var params = {
{/literal}
{foreach from=$params key=k item=v name='p'}
    '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}
{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};
// Show "Input Option Values"
var element = Ext.getCmp('modx-tv-elements');
var element_label = Ext.select('label[for="' + element.id + '"]');
if (element) {
    element.show();
    element_label.show();
}

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
        ,id: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,anchor: '100%'
        ,value: (params['allowBlank']) ? !(params['allowBlank'] === 0 || params['allowBlank'] === 'false') : true
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,html: _('required_desc')
        ,cls: 'desc-under'
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv|default}{literal}'
});
// ]]>
</script>
{/literal}
