<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text" class="textfield"
	value="{$tv->get('value')|escape}"
	{$style}
	tvtype="{$tv->type}"
	onchange="MODx.fireResourceFormChange();" 
/>

<script type="text/javascript">
{literal}
MODx.load({
{/literal}
    xtype: 'textfield'
    ,applyTo: 'tv{$tv->id}'
    ,value: '{$tv->value}'
    ,width: 300
{literal}
});
{/literal}
</script>