<select id="tv{$tv->id}" name="tv{$tv->id}">
{foreach from=$opts item=item}
	<option value="{$item.value}" {if $item.selected} selected="selected"{/if}>{$item.text}</option>
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
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}

        {if $params.title|default},title: '{$params.title|default}'{/if}
        {if $params.listWidth|default},listWidth: {$params.listWidth|default}{/if}
        ,maxHeight: {if $params.maxHeight|default}{$params.maxHeight|default}{else}300{/if}
        {if $params.typeAhead == 1 || $params.typeAhead == 'true'}
            ,typeAhead: true
            ,typeAheadDelay: {if $params.typeAheadDelay|default && $params.typeAheadDelay|default != ''}{$params.typeAheadDelay|default}{else}250{/if}
        {else}
            ,editable: false
            ,typeAhead: false
        {/if}
        {if $params.listEmptyText|default}
            ,listEmptyText: '{$params.listEmptyText|default}'
        {/if}
        ,forceSelection: {if $params.forceSelection|default && $params.forceSelection|default != 'false'}true{else}false{/if}
        ,msgTarget: 'under'

    {literal}
        ,listeners: { 'select': { fn:MODx.fireResourceFormChange, scope:this}}
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>
