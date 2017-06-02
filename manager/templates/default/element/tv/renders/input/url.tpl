<select id="tv{$tv->id}_prefix" name="tv{$tv->id}_prefix" onchange="MODx.fireResourceFormChange();">
{foreach from=$urls item=url}
	<option value="{$url}" {if $url == $selected|default}selected="selected"{/if}>{$url}</option>
{/foreach}
</select>
<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text"
	value="{$tv->get('processedValue')}"
	onchange="MODx.fireResourceFormChange();"
	class="textfield x-form-text x-form-field"
	style="width: 283px;"
/>
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.makeDroppable(Ext.get('tv{$tv->id}'));

    var fld = MODx.load({
        xtype: 'combo'
        ,transform: 'tv{$tv->id}_prefix'
        ,id: 'tv{$tv->id}_prefix'
        ,triggerAction: 'all'
        ,width: 100
        ,allowBlank: false
        ,maxHeight: 300
        ,typeAhead: false
        ,forceSelection: false
        ,msgTarget: 'under'
        ,listeners: { 'select': { fn:MODx.fireResourceFormChange, scope:this}}
    });

	fld.wrap.applyStyles({
		display: "inline-block"
	});
});
// ]]>
</script>
