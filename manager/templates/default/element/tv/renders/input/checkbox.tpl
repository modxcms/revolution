{foreach from=$opts item=item key=k}
	<label>
	<input id="tv{$tv->id}-{$k}" name="tv{$tv->id}[]"
		type="checkbox"
		value="{$item.value}"
        onchange="MODx.fireResourceFormChange();"
	/>
	</label>
	<br />
	<script type="text/javascript">
	{literal}
	MODx.load({
	{/literal}
	    xtype: 'checkbox'
	    ,applyTo: 'tv{$tv->id}-{$k}'
	    ,boxLabel: '{$item.text|escape:'javascript'}'
	    ,width: 300
	    ,checked: {if $item.checked}true{else}false{/if}    
	{literal}
	});
	{/literal}
	</script>
{/foreach}