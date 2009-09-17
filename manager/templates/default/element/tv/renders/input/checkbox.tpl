{foreach from=$opts item=item}
	<label>
	<input id="tv{$tv->id}" name="tv{$tv->id}_cb[]"
		type="checkbox" 
		value="{$item.value}"
		{if $item.checked} checked="checked"{/if}
		onchange="MODx.checkTV({$tv->id}); MODx.fireResourceFormChange();"
	/>
	{$item.text}
	<input type="hidden" name="tv{$tv->id}[]" id="tvh{$tv->id}"
	   value="{if $item.checked}{$item.value}{/if}"
	/>
	</label>
	<br />
{/foreach}