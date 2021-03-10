<input id="tv{$tv->id}" type="hidden" class="datefield"
    value="{$tv->value}" name="tv{$tv->id}"
    onblur="MODx.fireResourceFormChange();"/>

<script>
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
        {if $params.disabledDays|default},disabledDays: {$params.disabledDays|default}{/if}
        {if $params.minDateValue|default},minDateValue: '{$params.minDateValue|default}'{/if}
        {if $params.maxDateValue|default},maxDateValue: '{$params.maxDateValue|default}'{/if}
        {if $params.startDay|default},startDay: {$params.startDay|default}{/if}

        {if $params.minTimeValue|default},minTimeValue: '{$params.minTimeValue|default}'{/if}
        {if $params.maxTimeValue|default},maxTimeValue: '{$params.maxTimeValue|default}'{/if}
        {if $params.timeIncrement|default},timeIncrement: {$params.timeIncrement|default}{/if}
        {if $params.hideTime|default},hideTime: {$params.hideTime|default}{/if}

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
