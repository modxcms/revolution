<input id="tv{$tv->id}" type="hidden" class="datefield"
	value="{$tv->value}" name="tv{$tv->id}"
	onblur="MODx.fireResourceFormChange();"/>

<script type="text/javascript">
// <![CDATA[
{literal}
MODx.load({
{/literal}
    xtype: 'xdatetime'
    ,applyTo: 'tv{$tv->id}'
    ,width: '97%'
    ,name: 'tv{$tv->id}'
    ,dateFormat: MODx.config.manager_date_format
    ,timeFormat: MODx.config.manager_time_format
    ,dateWidth: 120
    ,timeWidth: 120
    ,allowBlank: true
    ,value: '{$tv->value}'
{literal}
    ,listeners: { 'change': { fn:MODx.fireResourceFormChange, scope:this}}
});
{/literal}
// ]]>
</script>