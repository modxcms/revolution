<div id="modx-tv-wprops-form{$tv}"></div>
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
        xtype: 'textfield'
        ,fieldLabel: _('date_format')
        ,name: 'prop_format'
        ,id: 'prop_format{/literal}{$tv}{literal}'
        ,value: params['format'] || '%A %d, %B %Y'
        ,listeners: oc
    },{
        xtype: 'combo'
        ,fieldLabel: _('date_use_current')
        ,name: 'prop_default'
        ,hiddenName: 'prop_default'
        ,id: 'prop_default{/literal}{$tv}{literal}'
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
    }]
    ,renderTo: 'modx-tv-wprops-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}