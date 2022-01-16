<input id="tv{$tv->id}" name="tv{$tv->id}"
    type="text" class="textfield"
    value="{$tv->get('value')|escape}"
    {$style|default}
    tvtype="{$tv->type}"
/>

<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    const fld = MODx.load({
    {/literal}
        xtype: 'numberfield'
        ,itemId: 'tv{$tv->id}'
        ,applyTo: 'tv{$tv->id}'
        ,width: '99%'
        ,enableKeyEvents: true
        ,autoStripChars: true
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        ,allowDecimals: {if $params.allowDecimals|default && $params.allowDecimals|default != 'false' && $params.allowDecimals|default != 'No'}true{else}false{/if}
        ,decimalPrecision: {if $params.decimalPrecision|default >= 0}{$params.decimalPrecision|default|string_format:"%d"}{else}2{/if}
        ,strictDecimalPrecision: {if $params.strictDecimalPrecision|default && $params.strictDecimalPrecision|default != 'false' && $params.strictDecimalPrecision|default != 'No'}true{else}false{/if}
        ,decimalSeparator: {if $params.decimalSeparator|default}'{$params.decimalSeparator|default}'{else}'.'{/if}
        {if $params.maxValue|default != '' && is_numeric($params.maxValue|default)},maxValue: {$params.maxValue|default}{/if}
        {if $params.minValue|default != '' && is_numeric($params.minValue|default)},minValue: {$params.minValue|default}{/if}
        ,msgTarget: 'under'
    {literal}
        ,listeners: {
            keydown: {
                fn: MODx.fireResourceFormChange,
                scope: this
            }
        }
    });
    MODx.makeDroppable(fld);
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>
