<select id="tv{$tv->id}_prefix" name="tv{$tv->id}_prefix" onchange="MODx.fireResourceFormChange();">
{foreach from=$urls item=url}
	<option value="{$url}" {if $url == $selected}selected="selected"{/if}>{$url}</option>
{/foreach}
</select>
<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text"
    style="width: 70%;"
	value="{$tv->get('processedValue')}"
	onchange="MODx.fireResourceFormChange();"
/>
<script type="text/javascript">
Ext.onReady(function() {
    MODx.makeDroppable(Ext.get('tv{$tv->id}'));
});
</script>