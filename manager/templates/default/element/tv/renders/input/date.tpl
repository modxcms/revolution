<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text" class="datefield"
	value="{$tv->get('value')}"
	onblur="MODx.fireResourceFormChange();"
/>

<script type="text/javascript">
// <![CDATA[
{literal}
MODx.load({
{/literal}
    xtype: 'datefield'
    ,applyTo: 'tv{$tv->id}'
    ,format: parent.MODx.config.manager_date_format
    ,width: '97%'
{literal}
    ,listeners: { 'change': { fn:MODx.fireResourceFormChange, scope:this}}
});
{/literal}
// ]]>
</script>