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
        xtype: 'numberfield'
        ,fieldLabel: _('image_hspace')
        ,name: 'prop_hspace'
        ,id: 'prop_hspace{/literal}{$tv|default}{literal}'
        ,value: params['hspace'] || ''
        ,width: 200
        ,listeners: oc
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('image_vspace')
        ,name: 'prop_vspace'
        ,id: 'prop_vspace{/literal}{$tv|default}{literal}'
        ,value: params['vspace'] || ''
        ,width: 200
        ,listeners: oc
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('image_border_size')
        ,name: 'prop_borsize'
        ,id: 'prop_borsize{/literal}{$tv|default}{literal}'
        ,value: params['borsize'] || ''
        ,width: 200
        ,listeners: oc
    },{
        xtype: 'combo'
        ,name: 'prop_align'
        ,hiddenName: 'prop_align'
        ,id: 'prop_align{/literal}{$tv|default}{literal}'
        ,fieldLabel: _('image_align')
        ,store: new Ext.data.SimpleStore({
            fields: ['v']
            ,data: [['none'],['baseline'],['top'],['middle'],['bottom'],['texttop'],['absmiddle'],['absbottom'],['left'],['right']]
        })
        ,displayField: 'v'
        ,valueField: 'v'
        ,mode: 'local'
        ,editable: true
        ,forceSelection: false
        ,typeAhead: false
        ,triggerAction: 'all'
        ,value: params['align'] || 'none'
        ,anchor: '100%'
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('name')
        ,name: 'prop_name'
        ,id: 'prop_name{/literal}{$tv|default}{literal}'
        ,value: params['name'] || ''
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
        ,fieldLabel: _('id')
        ,name: 'prop_id'
        ,id: 'prop_id{/literal}{$tv|default}{literal}'
        ,value: params['id'] || ''
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
