<div id="tv{$tv->id}-cb"></div>

<script type="text/javascript">
// <![CDATA[
{literal}
MODx.load({
{/literal}
    xtype: 'radiogroup'
    ,id: 'tv{$tv->id}'
    ,vertical: true
    ,columns: 1
    ,renderTo: 'tv{$tv->id}-cb'
    ,name: 'tv-{$tv->id}'
    ,autoHeight: true
    ,width: '97%'
    
    ,items: [{foreach from=$opts item=item key=k name=cbs}
    {literal}{{/literal}
        name: 'tv{$tv->id}'
        ,id: 'tv{$tv->id}-{$k}'
        ,boxLabel: '{$item.text|escape:"javascript"}'
        ,checked: {if $item.checked}true{else}false{/if}
        ,inputValue: '{$item.value}'
        ,value: '{$item.value}'
    {literal}}{/literal}{if NOT $smarty.foreach.cbs.last},{/if}
    {/foreach}]
{literal}}{/literal});

{foreach from=$opts item=item key=k name=cbs}
Ext.getCmp('tv{$tv->id}-{$k}').on('check',MODx.fireResourceFormChange);
{/foreach}
// ]]>
</script>