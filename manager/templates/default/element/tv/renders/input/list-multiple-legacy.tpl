<select id="tv{$tv->id}" name="tv{$tv->id}[]" multiple {if $params.listWidth}width="$params.listWidth"{else}width="400"{/if} {if $params.listHeight}height="$params.listHeight"{else}height="100"{/if} style="{if $params.listWidth}width: {$params.listWidth}px;{else}width: 400px;{/if}{if $params.listHeight}height: {$params.listHeight}px;{else}height: 100px;{/if}" class="modx-tv-legacy-select">
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