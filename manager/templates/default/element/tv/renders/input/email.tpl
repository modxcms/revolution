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
        ,vtype: 'email'
        ,enableKeyEvents: true
        ,msgTarget: 'under'
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        {if $params.maxLength|default != '' && $params.maxLength|default > 0}{if $params.minLength|default != '' && $params.minLength|default >= 0 && $params.maxLength|default > $params.minLength|default},maxLength: {$params.maxLength|string_format:"%d"}{/if} {/if}
        {if $params.minLength|default != '' && $params.minLength|default >= 0},minLength: {$params.minLength|string_format:"%d"}{/if}
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
