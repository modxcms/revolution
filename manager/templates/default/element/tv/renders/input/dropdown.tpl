<select id="tv{$tv->id}" name="tv{$tv->id}"
	class="combobox"
	onchange="MODx.fireResourceFormChange();">
{foreach from=$tvitems item=item}
	<option value="{$item.value}" {if $item.value EQ $tv->get('value')} selected="selected"{/if}>{$item.text}</option>
{/foreach}
</select>


<script type="text/javascript">
{literal}
MODx.load({
{/literal}
    xtype: 'combo'
    ,transform: 'tv{$tv->id}'
    ,id: 'tv{$tv->id}'
    ,triggerAction: 'all'
    ,typeAhead: false
    ,editable: false
    ,width: 300
{literal}
});
{/literal}
</script>