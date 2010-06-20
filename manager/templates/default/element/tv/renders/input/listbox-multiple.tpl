<select id="tv{$tv->id}" name="tv{$tv->id}[]"
	multiple="multiple"
	onselect="MODx.fireResourceFormChange();"
	onchange="MODx.fireResourceFormChange();"
	size="8"
    style="width: 97%;"
>
{foreach from=$opts item=item}
	<option value="{$item.value}" {if $item.selected} selected="selected"{/if}>{$item.text}</option>
{/foreach}
</select>