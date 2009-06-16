<select id="tv{$tv->id}" name="tv{$tv->id}"
	class="combobox"
	onchange="MODx.fireResourceFormChange();">
{foreach from=$tvitems item=item}
	<option value="{$item.value}" {if $item.value EQ $tv->get('value')} selected="selected"{/if}>{$item.text}</option>
{/foreach}
</select>