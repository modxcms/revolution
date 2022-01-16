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

<script>
// <![CDATA[
Ext.onReady(function() {
    MODx.makeDroppable(Ext.get('tv{$tv->id}'));
    const protocols = MODx.load({
        xtype: 'combo'
        ,transform: 'tv{$tv->id}_prefix'
        ,id: 'tv{$tv->id}_prefix'
        ,triggerAction: 'all'
        ,width: 100
        ,maxHeight: 300
        ,typeAhead: false
        ,forceSelection: false
        ,allowBlank: true
        ,msgTarget: 'under'
        ,listeners: {
            select: {
                fn: MODx.fireResourceFormChange,
                scope: this
            }
        }
    });
    protocols.wrap.applyStyles({
        display: "inline-block"
    });

    const fld = MODx.load({
        xtype: 'textfield'
        ,itemId: 'tv{$tv->id}'
        ,applyTo: 'tv{$tv->id}'
        ,enableKeyEvents: true
        ,msgTarget: 'under'
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
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
// ]]>
</script>
