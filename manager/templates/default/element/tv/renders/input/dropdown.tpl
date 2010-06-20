<select id="tv{$tv->id}" name="tv{$tv->id}">
{foreach from=$tvitems item=item}
	<option value="{$item.value}" {if $item.selected} selected="selected"{/if}>{$item.text}</option>
{/foreach}
</select>


<script type="text/javascript">
// <![CDATA[
{literal}
MODx.load({
{/literal}
    xtype: 'combo'
    ,transform: 'tv{$tv->id}'
    ,id: 'tv{$tv->id}'
    ,triggerAction: 'all'
    ,typeAhead: false
    ,editable: false
    ,width: '97%'
{literal}
    ,listeners: { 'select': { fn:MODx.fireResourceFormChange, scope:this}}
});
{/literal}
// ]]>
</script>