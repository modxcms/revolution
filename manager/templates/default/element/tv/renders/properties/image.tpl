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
        ,fieldLabel: _('image_alt')
        ,name: 'prop_alttext'
        ,id: 'prop_alttext{/literal}{$tv|default}{literal}'
        ,value: params['alttext'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('id')
        ,name: 'prop_id'
        ,id: 'prop_id{/literal}{$tv|default}{literal}'
        ,value: params['id'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('class')
        ,name: 'prop_class'
        ,id: 'prop_class{/literal}{$tv|default}{literal}'
        ,value: params['class'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('style')
        ,name: 'prop_style'
        ,id: 'prop_style{/literal}{$tv|default}{literal}'
        ,value: params['style'] || ''
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('attributes')
        ,name: 'prop_attributes'
        ,id: 'prop_attributes{/literal}{$tv|default}{literal}'
        ,value: params['attributes'] || ''
        ,anchor: '100%'
        ,listeners: oc
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv|default}{literal}'
});
// ]]>
</script>
{/literal}
