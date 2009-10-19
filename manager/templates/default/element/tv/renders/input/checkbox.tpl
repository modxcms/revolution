<div id="tv{$tv->id}-cb"></div>

<script type="text/javascript">
{literal}
MODx.load({
{/literal}
    xtype: 'checkboxgroup'
    ,id: 'tv{$tv->id}'
    ,fieldLabel: 'test'
    ,width: 300
    ,vertical: true
    ,renderTo: 'tv{$tv->id}-cb'
    ,name: 'tv-{$tv->id}'
    
    ,items: [{foreach from=$opts item=item key=k name=cbs}
    {literal}{{/literal}
        name: 'tv{$tv->id}[]'
        ,id: 'tv{$tv->id}-{$k}'
        ,boxLabel: '{$item.text|escape:"javascript"}'
        ,checked: {if $item.checked}true{else}false{/if}
        ,inputValue: '{$item.value}'
        ,value: '{$item.value}'
    {literal}}{/literal}{if NOT $smarty.foreach.cbs.last},{/if}
    {/foreach}]
    
    ,listeners: {literal}{
        'change': {fn:MODx.fireResourceFormChange}
    }{/literal}
});

Ext.get('tvdef{$tv->id}').dom.value = "{$cbdefaults}";
</script>