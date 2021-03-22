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
{literal}
Ext.onReady(function() {
{/literal}

    MODx.makeDroppable(Ext.get('tv{$tv->id}'));
{literal}
    var fld = MODx.load({
        xtype: 'combo'
{/literal}
        ,transform: 'tv{$tv->id}_prefix'
        ,id: 'tv{$tv->id}_prefix'
{literal}
        ,triggerAction: 'all'
        ,width: 100
        ,allowBlank: true
        ,maxHeight: 300
        ,typeAhead: false
        ,forceSelection: false
        ,msgTarget: 'under'
        ,listeners: {
            'select': {
                fn:MODx.fireResourceFormChange,
                scope:this
            }
        }
    });

    fld.wrap.applyStyles({
        display: "inline-block"
    });

    var fld = MODx.load({
        xtype: 'textfield'
{/literal}
        ,applyTo: 'tv{$tv->id}'
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
{literal}
        ,enableKeyEvents: true
        ,msgTarget: 'under'
        ,listeners: {
            'keydown': {
                fn:MODx.fireResourceFormChange,
                scope:this
            }
        }
    });
    MODx.makeDroppable(fld);
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>
