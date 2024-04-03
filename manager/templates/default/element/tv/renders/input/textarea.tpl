<textarea id="tv{$tv->id}" name="tv{$tv->id}">{$tv->get('value')|escape}</textarea>

<script>
// <![CDATA[
document.getElementById('tv{$tv->id}').setAttribute('autocomplete', globalAutoCompleteSetting);
{literal}
Ext.onReady(function() {
    const 
        defaultHeight = 140,
        fld = MODx.load({
        {/literal}
        xtype: 'textarea',
        itemId: 'tv{$tv->id}',
        applyTo: 'tv{$tv->id}',
        {if $tv->get('value') != ''}
            value: '{$tv->get('value')|escape:'javascript'}',
        {/if}
        {if $params.textareaGrow == 1 || $params.textareaGrow == 'true'}
            boxMinHeight: {if $params.inputHeight != ''}{$params.inputHeight}{else}{literal}defaultHeight{/literal}{/if},
            grow: true,
            growMin: {if $params.inputHeight != ''}{$params.inputHeight}{else}{literal}defaultHeight{/literal}{/if},
            growMax: 1200,
        {else}
            height: {if $params.inputHeight != ''}{$params.inputHeight}{else}{literal}defaultHeight{/literal}{/if},
        {/if}
        {if $params.textareaResizable == 1 || $params.textareaResizable == 'true'}
            ctCls: 'resizable',
        {/if}
        width: '99%',
        enableKeyEvents: true,
        msgTarget: 'under',
        allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if},
    {literal}
        listeners: {
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
