<select id="tv{$tv->id}" name="tv{$tv->id}[]"
    multiple="multiple"
    onselect="MODx.fireResourceFormChange();"
    onchange="MODx.fireResourceFormChange();"
    size="8"
>
    {foreach from=$opts item=item}
        <option value="{$item.value}" {if $item.selected} selected="selected"{/if}>{$item.text}</option>
    {/foreach}
</select>

<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    const fld = MODx.load({
    {/literal}
        xtype:'superboxselect'
        ,transform: 'tv{$tv->id}'
        ,id: 'tv{$tv->id}'
        ,itemId: 'tv{$tv->id}'
        ,triggerAction: 'all'
        ,mode: 'local'
        ,extraItemCls: 'x-tag'
        ,expandBtnCls: 'x-form-trigger'
        ,clearBtnCls: 'x-form-trigger'
        ,listClass: 'modx-superboxselect modx-tv-listbox-multiple'
        ,width: 400
        ,maxHeight: 300
        ,displayField: 'text'
        ,valueField: 'value'
        ,resizable: true
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        {if $params.typeAhead == 1 || $params.typeAhead == 'true'}
            ,typeAhead: true
            ,typeAheadDelay: {if $params.typeAheadDelay|default && $params.typeAheadDelay|default != ''}{$params.typeAheadDelay|default}{else}250{/if}
            ,editable: true
        {else}
            ,typeAhead: false
            ,editable: false
        {/if}
        {if $params.title|default}
            ,title: '{$params.title|default|escape}'
        {/if}
        {if $params.listEmptyText|default}
            ,listEmptyText: '{$params.listEmptyText|default|escape}'
        {/if}
        ,allowAddNewData: {if $params.forceSelection|default && $params.forceSelection|default != 'false'}false{else}true{/if}
        ,addNewDataOnBlur: true
        ,stackItems: {if $params.stackItems|default && $params.stackItems|default != 'false'}true{else}false{/if}
        ,msgTarget: 'under'
        {literal}
        ,listeners: {
            select: {
                fn: MODx.fireResourceFormChange,
                scope: this
            },
            beforeadditem: function(cmp, selectedIndex, record) {
                if (selectedIndex == '') {
                    return false;
                }
                MODx.fireResourceFormChange();
            },
            newitem: {
                fn: function(bs, v, f) {
                    let item = {};
                    item[bs.valueField] = v;
                    item[bs.displayField] = v;
                    bs.addNewItem(item);
                    MODx.fireResourceFormChange();
                    return true;
                },
                scope: this
            },
            beforeremoveitem: {
                fn: MODx.fireResourceFormChange,
                scope: this
            },
            clear: {
                fn: MODx.fireResourceFormChange,
                scope: this
            }
        }
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>
