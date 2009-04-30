{foreach from=$opts item=item}
	<label>
	<input id="tv{$tv->id}-{$item.value}" name="tv{$tv->id}[]"
		type="checkbox" 
		value="{$item.value}"
		{if $item.checked} checked="checked"{/if}
		onchange="javascript:triggerDirtyField(this);"
	/>
	{$item.text}
	</label>
	<br />
{/foreach}