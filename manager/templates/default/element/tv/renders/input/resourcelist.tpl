<select id="tv{$tv->id}" name="tv{$tv->id}">
{foreach from=$opts item=item}
    <option value="{$item.value|escape}" {if $item.selected} selected="selected"{/if}>{$item.text|escape}</option>
{/foreach}
</select>

<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld = MODx.load({
    {/literal}
        xtype: 'combo'
        ,transform: 'tv{$tv->id}'
        ,id: 'tv{$tv->id}'
        ,triggerAction: 'all'
        ,width: 400
        ,maxHeight: 300
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}

        {if $params.title|default}
            ,title: '{$params.title|default|escape}'
        {/if}
        {if $params.listWidth|default}
            ,listWidth: {$params.listWidth}
        {/if}
        ,maxHeight: {if $params.maxHeight|default}{$params.maxHeight}{else}300{/if}
        ,tpl: {literal}'<tpl for="."><div class="x-combo-list-item">{text:htmlEncode}</div></tpl>'{/literal}
        {if $params.typeAhead == 1 || $params.typeAhead == 'true'}
            ,typeAhead: true
            ,typeAheadDelay: {if $params.typeAheadDelay|default && $params.typeAheadDelay|default != ''}{$params.typeAheadDelay|default}{else}250{/if}
            ,selectOnFocus: true
        {else}
            ,typeAhead: false
            ,editable: false
        {/if}
        ,forceSelection: true
        ,msgTarget: 'under'

        {if $params.allowBlank == 1 || $params.allowBlank == 'true'}
        {else}
        {literal}
        ,validator: function(v) {
            if (Ext.isEmpty(v) || v == '' || v == '-') {
                return _('field_required');
            }
            return true;
        }
        {/literal}
        {/if}
        {literal}
        ,listeners: { 'select': { fn:MODx.fireResourceFormChange, scope:this}}
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>
