<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text" class="textfield"
	value="{$tv->get('value')|escape}"
	{$style}
	tvtype="{$tv->type}"
/>

<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld = MODx.load({
    {/literal}
        xtype: 'numberfield'
        ,applyTo: 'tv{$tv->id}'
        ,width: 400
        ,enableKeyEvents: true
        ,autoStripChars: true
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if} 
        ,allowDecimals: {if $params.allowDecimals && $params.allowDecimals != 'false' && $params.allowDecimals != 'No'}true{else}false{/if} 
        ,allowNegative: {if $params.allowNegative && $params.allowNegative != 'false' && $params.allowNegative != 'No'}true{else}false{/if} 
        ,decimalPrecision: {if $params.decimalPrecision >= 0}{$params.decimalPrecision|string_format:"%d"}{else}2{/if} 
        ,decimalSeparator: {if $params.decimalSeparator}'{$params.decimalSeparator}'{else}'.'{/if} 
        {if $params.maxValue != '' && is_numeric($params.maxValue)},maxValue: {$params.maxValue}{/if} 
        {if $params.minValue != '' && is_numeric($params.minValue)},minValue: {$params.minValue}{/if} 
        ,msgTarget: 'under'
    {literal}
        ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
    });
    MODx.makeDroppable(fld);
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>
