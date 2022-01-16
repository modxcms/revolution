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
        xtype: 'textfield'
        ,itemId: 'tv{$tv->id}'
        ,applyTo: 'tv{$tv->id}'
        ,width: '99%'
        ,enableKeyEvents: true
        ,msgTarget: 'under'
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        {if $params.minLength|default},minLength: {$params.minLength|default}{/if}
        {if $params.maxLength|default},maxLength: {$params.maxLength|default}{/if}
        {if $params.regex|default},regex: new RegExp(/{$params.regex|default|escape}/){/if}
        {if $params.regexText|default},regexText: '{$params.regexText|default|escape}'{/if}
    {literal}
        ,listeners: {
            keydown: {
                fn: MODx.fireResourceFormChange,
                scope: this
            }
        }
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
    MODx.makeDroppable(fld);
});
{/literal}
// ]]>
</script>
