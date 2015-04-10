<input id="tv{$tv->id}" type="hidden" class="datefield"
	value="{$tv->value}" name="tv{$tv->id}"
	onblur="MODx.fireResourceFormChange();"/>

<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld = MODx.load({
    {/literal}
        xtype: 'xdatetime'
        ,applyTo: 'tv{$tv->id}'
        ,name: 'tv{$tv->id}'
        ,dateFormat: MODx.config.manager_date_format
        ,timeFormat: MODx.config.manager_time_format
        {if $params.disabledDates},disabledDates: {$params.disabledDates}{/if}
        {if $params.disabledDays},disabledDays: {$params.disabledDays}{/if}
        {if $params.minDateValue},minDateValue: '{$params.minDateValue}'{/if}
        {if $params.maxDateValue},maxDateValue: '{$params.maxDateValue}'{/if}
        {if $params.startDay},startDay: {$params.startDay}{/if}

        {if $params.minTimeValue},minTimeValue: '{$params.minTimeValue}'{/if}
        {if $params.maxTimeValue},maxTimeValue: '{$params.maxTimeValue}'{/if}
        {if $params.timeIncrement},timeIncrement: {$params.timeIncrement}{/if}
        {if $params.hideTime},hideTime: {$params.hideTime}{/if}

        ,dateWidth: 198
        ,timeWidth: 198
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        ,value: '{$tv->value}'
        ,msgTarget: 'under'
    {literal}
        ,listeners: { 'change': { fn:MODx.fireResourceFormChange, scope:this}}
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>