<select id="tv{$tv->id}" name="tv{$tv->id}[]" multiple {if $params.listWidth}width="$params.listWidth"{/if} {if $params.listHeight}height="$params.listHeight"{/if} style="{if $params.listWidth}width: {$params.listWidth}px;{/if}{if $params.listHeight}height: {$params.listHeight}px;{/if}">
{foreach from=$opts item=item}
	<option value="{$item.value}" {if $item.selected} selected="selected"{/if}>{$item.text}</option>
{/foreach}
</select>

<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var el = Ext.get('{/literal}tv{$tv->id}{literal}');
    el.on('change', MODx.fireResourceFormChange, el);
});
{/literal}
// ]]>
</script>