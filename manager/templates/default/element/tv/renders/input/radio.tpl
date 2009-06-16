{foreach from=$opts item=item}
<label>
	<input id="tv{$tv->id}" name="tv{$tv->id}"
		type="radio"
		value="{$item.value}"
		{if $item.checked} checked="checked"{/if}
		onchange="MODx.fireResourceFormChange();" 
	/>
	{$item.text}
</label>
<br />
{/foreach}
