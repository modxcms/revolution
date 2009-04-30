<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text" class="datefield"
	value="{$tv->get('value')}"
	onblur="javascript:triggerDirtyField(this);"
/>

<script type="text/javascript">
{literal}
MODx.load({
{/literal}
    xtype: 'datefield'
    ,applyTo: 'tv{$tv->id}'
    ,format: 'Y-m-d'
    ,value: '{$tv->value}' 
{literal}
});
{/literal}
</script>