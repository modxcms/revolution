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
        xtype: 'textfield'
        ,fieldLabel: _('disabled_dates')
        ,description: _('disabled_dates_desc')
        ,name: 'inopt_disabledDates'
        ,id: 'inopt_disabledDates{/literal}{$tv}{literal}'
        ,value: params['disabledDates'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('disabled_days')
        ,description: _('disabled_days_desc')
        ,name: 'inopt_disabledDays'
        ,id: 'inopt_disabledDays{/literal}{$tv}{literal}'
        ,value: params['disabledDays'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'datefield'
        ,fieldLabel: _('earliest_date')
        ,description: _('earliest_date_desc')
        ,name: 'inopt_minDateValue'
        ,id: 'inopt_minDateValue{/literal}{$tv}{literal}'
        ,value: params['minDateValue'] || ''
        ,width: 300
        ,listeners: oc
        ,format: MODx.config.manager_date_format
    },{
        xtype: 'timefield'
        ,fieldLabel: _('earliest_time')
        ,description: _('earliest_time_desc')
        ,name: 'inopt_minTimeValue'
        ,id: 'inopt_minTimeValue{/literal}{$tv}{literal}'
        ,value: params['minTimeValue'] || ''
        ,width: 300
        ,listeners: oc
        ,format: MODx.config.manager_time_format
    },{
        xtype: 'datefield'
        ,fieldLabel: _('latest_date')
        ,description: _('latest_date_desc')
        ,name: 'inopt_maxDateValue'
        ,id: 'inopt_maxDateValue{/literal}{$tv}{literal}'
        ,value: params['maxDateValue'] || ''
        ,width: 300
        ,listeners: oc
        ,format: MODx.config.manager_date_format
    },{
        xtype: 'timefield'
        ,fieldLabel: _('latest_time')
        ,description: _('latest_time_desc')
        ,name: 'inopt_maxTimeValue'
        ,id: 'inopt_maxTimeValue{/literal}{$tv}{literal}'
        ,value: params['maxTimeValue'] || ''
        ,width: 300
        ,listeners: oc
        ,format: MODx.config.manager_time_format
    },{
        xtype: 'textfield'
        ,fieldLabel: _('start_day')
        ,description: _('start_day_desc')
        ,name: 'inopt_startDay'
        ,id: 'inopt_startDay{/literal}{$tv}{literal}'
        ,value: params['startDay'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('time_increment')
        ,description: _('time_increment_desc')
        ,name: 'inopt_timeIncrement'
        ,id: 'inopt_timeIncrement{/literal}{$tv}{literal}'
        ,value: params['timeIncrement'] || ''
        ,width: 300
        ,listeners: oc
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}