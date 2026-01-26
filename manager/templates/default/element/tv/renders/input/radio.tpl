<div id="tv{$tv->id}-cb"></div>

<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    const fld = MODx.load({
    {/literal}
        xtype: 'radiogroup',
        id: 'tv{$tv->id}',
        itemId: 'tv{$tv->id}',
        {if $params.columnDirection == 'vertical' || $params.columnDirection == null}vertical: true,{/if}
        columns: {if $params.columns|default}{$params.columns|default}{else}1{/if},
        {if $params.wrapColumnText == 1 || $params.wrapColumnText == 'true'}ctCls: 'wrap-columns column-width-{if $params.columnWidth}{$params.columnWidth|escape:"javascript"}{else}medium{/if}',{/if}
        renderTo: 'tv{$tv->id}-cb',
        allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if},
        hideMode: 'offsets',
        msgTarget: 'under',
        items: [{foreach from=$opts item=item key=k name=cbs}
        {literal}{{/literal}
            name: 'tv{$tv->id}',
            id: 'tv{$tv->id}-{$k}',
            boxLabel: '{$item.text|escape:"javascript"}',
            checked: {if $item.checked}true{else}false{/if},
            inputValue: {$item.value},
            value: {$item.value},
            {literal}
            listeners: {
                check: MODx.fireResourceFormChange
            }
            {/literal}

        {literal}}{/literal}{if NOT $smarty.foreach.cbs.last},{/if}
        {/foreach}]
    {literal}}{/literal});
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
// ]]>
</script>
