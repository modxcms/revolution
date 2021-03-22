<div id="tv-input-properties-form{$tv|default}"></div>
{literal}

<script>
// <![CDATA[
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};

var element = Ext.getCmp('modx-tv-elements');
if (element) {
  element.hide();
}

MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,autoHeight: true
    ,cls: 'form-with-labels'
    ,border: false
    ,labelAlign: 'top'
    ,items: [{
        xtype: 'combo-boolean'
        ,fieldLabel: _('required')
        ,description: MODx.expandHelp ? '' : _('required_desc')
        ,name: 'inopt_allowBlank'
        ,hiddenName: 'inopt_allowBlank'
        ,id: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,width: 200
        ,value: (params['allowBlank']) ? !(params['allowBlank'] === 0 || params['allowBlank'] === 'false') : true
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowBlank{/literal}{$tv|default}{literal}'
        ,html: _('required_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('disabled_dates')
        ,description: MODx.expandHelp ? '' : _('disabled_dates_desc')
        ,name: 'inopt_disabledDates'
        ,id: 'inopt_disabledDates{/literal}{$tv|default}{literal}'
        ,value: params['disabledDates'] || ''
        ,anchor: '98%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_disabledDates{/literal}{$tv|default}{literal}'
        ,html: _('disabled_dates_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('disabled_days')
        ,description: MODx.expandHelp ? '' : _('disabled_days_desc')
        ,name: 'inopt_disabledDays'
        ,id: 'inopt_disabledDays{/literal}{$tv|default}{literal}'
        ,value: params['disabledDays'] || ''
        ,anchor: '98%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_disabledDays{/literal}{$tv|default}{literal}'
        ,html: _('disabled_days_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'datefield'
        ,fieldLabel: _('earliest_date')
        ,description: MODx.expandHelp ? '' : _('earliest_date_desc')
        ,name: 'inopt_minDateValue'
        ,id: 'inopt_minDateValue{/literal}{$tv|default}{literal}'
        ,value: params['minDateValue'] || ''
        ,width: 200
        ,listeners: oc
        ,format: MODx.config.manager_date_format
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_minDateValue{/literal}{$tv|default}{literal}'
        ,html: _('earliest_date_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'timefield'
        ,fieldLabel: _('earliest_time')
        ,description: MODx.expandHelp ? '' : _('earliest_time_desc')
        ,name: 'inopt_minTimeValue'
        ,id: 'inopt_minTimeValue{/literal}{$tv|default}{literal}'
        ,value: params['minTimeValue'] || ''
        ,width: 200
        ,listeners: oc
        ,format: MODx.config.manager_time_format
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_minTimeValue{/literal}{$tv|default}{literal}'
        ,html: _('earliest_time_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'datefield'
        ,fieldLabel: _('latest_date')
        ,description: MODx.expandHelp ? '' : _('latest_date_desc')
        ,name: 'inopt_maxDateValue'
        ,id: 'inopt_maxDateValue{/literal}{$tv|default}{literal}'
        ,value: params['maxDateValue'] || ''
        ,width: 200
        ,listeners: oc
        ,format: MODx.config.manager_date_format
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_maxDateValue{/literal}{$tv|default}{literal}'
        ,html: _('latest_date_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'timefield'
        ,fieldLabel: _('latest_time')
        ,description: MODx.expandHelp ? '' : _('latest_time_desc')
        ,name: 'inopt_maxTimeValue'
        ,id: 'inopt_maxTimeValue{/literal}{$tv|default}{literal}'
        ,value: params['maxTimeValue'] || ''
        ,width: 200
        ,listeners: oc
        ,format: MODx.config.manager_time_format
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_maxTimeValue{/literal}{$tv|default}{literal}'
        ,html: _('latest_time_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('start_day')
        ,description: MODx.expandHelp ? '' : _('start_day_desc')
        ,name: 'inopt_startDay'
        ,id: 'inopt_startDay{/literal}{$tv|default}{literal}'
        ,value: params['startDay'] || ''
        ,width: 100
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_startDay{/literal}{$tv|default}{literal}'
        ,html: _('start_day_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('time_increment')
        ,description: MODx.expandHelp ? '' : _('time_increment_desc')
        ,name: 'inopt_timeIncrement'
        ,id: 'inopt_timeIncrement{/literal}{$tv|default}{literal}'
        ,value: params['timeIncrement'] || ''
        ,width: 100
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_timeIncrement{/literal}{$tv|default}{literal}'
        ,html: _('time_increment_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('hide_time')
        ,description: MODx.expandHelp ? '' : _('hide_time')
        ,name: 'inopt_hideTime'
        ,hiddenName: 'inopt_hideTime'
        ,id: 'inopt_hideTime{/literal}{$tv|default}{literal}'
        ,width: 200
        ,value: (params['hideTime']) ? !(params['hideTime'] === 0 || params['hideTime'] === 'false') : false
        ,listeners: oc
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv|default}{literal}'
});
// ]]>
</script>
{/literal}
