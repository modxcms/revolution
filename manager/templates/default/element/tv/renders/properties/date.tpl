<div id="modx-tv-wprops-form{$tv}"></div>
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
        ,fieldLabel: _('date_format')
        ,name: 'prop_format'
        ,value: params['format'] || '%A %d, %B %Y'
    },{
        xtype: 'combo'
        ,fieldLabel: _('date_use_current')
        ,name: 'prop_default'
        ,hiddenName: 'prop_default'
        ,store: new Ext.data.SimpleStore({
            fields: ['v','d']
            ,data: [['yes',_('yes')],['no',_('no')]]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,editable: false
        ,forceSelection: true
        ,typeAhead: false
        ,triggerAction: 'all'
        ,value: params['default'] || 'no'
    }]
    ,renderTo: 'modx-tv-wprops-form{/literal}{$tv}{literal}'
});
</script>
{/literal}