<select id="tv{$tv->id}" name="tv{$tv->id}[]" multiple {if $params.listWidth|default}width="$params.listWidth|default"{else}width="400"{/if} {if $params.listHeight|default}height="$params.listHeight|default"{else}height="100"{/if} style="{if $params.listWidth|default}width: {$params.listWidth|default}px;{else}width: 400px;{/if}{if $params.listHeight|default}height: {$params.listHeight|default}px;{else}height: 100px;{/if}" class="modx-tv-legacy-select">
{foreach from=$opts item=item}
	<option value="{$item.value}" {if $item.selected} selected="selected"{/if}>{$item.text}</option>
{/foreach}
</select>

<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    var el = Ext.get('{/literal}tv{$tv->id}{literal}');
    el.on('change', MODx.fireResourceFormChange, el);
});
{/literal}
// ]]>
</script>
