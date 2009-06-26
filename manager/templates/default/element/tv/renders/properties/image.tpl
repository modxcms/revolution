<div id="tv-wprops-form{$tv}"></div>
{literal}

<script type="text/javascript">
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,autoHeight: true
    ,labelWidth: 150
    ,border: false
    ,items: [{
        xtype: 'textfield'
        ,fieldLabel: _('image_alt')
        ,name: 'prop_alttext'
        ,value: params['alttext'] || ''
        ,width: 300
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('image_hspace')
        ,name: 'prop_hspace'
        ,value: params['hspace'] || ''
        ,width: 300
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('image_vspace')
        ,name: 'prop_vspace'
        ,value: params['vspace'] || ''
        ,width: 300
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('image_border_size')
        ,name: 'prop_borsize'
        ,value: params['borsize'] || ''
        ,width: 300
    },{
        xtype: 'combo'
        ,name: 'prop_align'
        ,hiddenName: 'prop_align'
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
        ,width: 300
    },{
        xtype: 'textfield'
        ,fieldLabel: _('name')
        ,name: 'prop_name'
        ,value: params['name'] || ''
        ,width: 300
    },{
        xtype: 'textfield'
        ,fieldLabel: _('class')
        ,name: 'prop_class'
        ,value: params['class'] || ''
        ,width: 300
    },{
        xtype: 'textfield'
        ,fieldLabel: _('id')
        ,name: 'prop_id'
        ,value: params['id'] || ''
        ,width: 300
    },{
        xtype: 'textfield'
        ,fieldLabel: _('style')
        ,name: 'prop_style'
        ,value: params['style'] || ''
        ,width: 300
    },{
        xtype: 'textfield'
        ,fieldLabel: _('attributes')
        ,name: 'prop_attributes'
        ,value: params['attributes'] || ''
        ,width: 300
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv}{literal}'
});
</script>
{/literal}