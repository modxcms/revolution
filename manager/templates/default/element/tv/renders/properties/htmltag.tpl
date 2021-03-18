<div id="tv-wprops-form{$tv|default}"></div>
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
    ,cls: 'form-with-labels'
    ,labelAlign: 'top'
    ,border: false
    ,items: [{
        xtype: 'textfield'
        ,fieldLabel: _('tag_name')
        ,name: 'prop_tagname'
        ,id: 'prop_tagname{/literal}{$tv|default}{literal}'
        ,value: params['tagname'] || 'div'
        ,listeners: oc
        ,anchor: '100%'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('id')
        ,name: 'prop_id'
        ,id: 'prop_id{/literal}{$tv|default}{literal}'
        ,value: params['id'] || ''
        ,listeners: oc
        ,anchor: '100%'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('class')
        ,name: 'prop_class'
        ,id: 'prop_class{/literal}{$tv|default}{literal}'
        ,value: params['class'] || ''
        ,listeners: oc
        ,anchor: '100%'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('style')
        ,name: 'prop_style'
        ,id: 'prop_style{/literal}{$tv|default}{literal}'
        ,value: params['style'] || ''
        ,listeners: oc
        ,anchor: '100%'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('attributes')
        ,name: 'prop_attributes'
        ,id: 'prop_attributes{/literal}{$tv|default}{literal}'
        ,value: params['attributes'] || ''
        ,listeners: oc
        ,anchor: '100%'
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv|default}{literal}'
});
// ]]>
</script>
{/literal}
