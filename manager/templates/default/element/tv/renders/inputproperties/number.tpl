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
        ,value: params['allowBlank'] == 0 || params['allowBlank'] == 'false' ? false : true
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowBlank{/literal}{$tv}{literal}'
        ,html: _('required_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('number_allowdecimals')
        ,name: 'inopt_allowDecimals'
        ,id: 'inopt_allowDecimals{/literal}{$tv}{literal}'
        ,value: params['allowDecimals'] || true
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowDecimals{/literal}{$tv}{literal}'
        ,html: _('allowdecimals_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('number_allownegative')
        ,name: 'inopt_allowNegative'
        ,id: 'inopt_allowNegative{/literal}{$tv}{literal}'
        ,value: params['allowNegative'] || true
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowNegative{/literal}{$tv}{literal}'
        ,html: _('allownegative_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'numberfield'
        ,fieldLabel: _('number_decimalprecision')
        ,name: 'inopt_decimalPrecision'
        ,id: 'inopt_decimalPrecision{/literal}{$tv}{literal}'
        ,value: params['decimalPrecision'] || 2
        ,width: 100
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_decimalPrecision{/literal}{$tv}{literal}'
        ,html: _('decimalprecision_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('number_decimalseparator')
        ,name: 'inopt_decimalSeparator'
        ,id: 'inopt_decimalSeparator{/literal}{$tv}{literal}'
        ,value: params['decimalSeparator'] || '.'
        ,width: 100
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_decimalSeparator{/literal}{$tv}{literal}'
        ,html: _('decimalseparator_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('number_maxvalue')
        ,name: 'inopt_maxValue'
        ,id: 'inopt_maxValue{/literal}{$tv}{literal}'
        ,value: params['maxValue'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_maxValue{/literal}{$tv}{literal}'
        ,html: _('maxvalue_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('number_minvalue')
        ,name: 'inopt_minValue'
        ,id: 'inopt_minValue{/literal}{$tv}{literal}'
        ,value: params['minValue'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_minValue{/literal}{$tv}{literal}'
        ,html: _('minvalue_desc')
        ,cls: 'desc-under'
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}
