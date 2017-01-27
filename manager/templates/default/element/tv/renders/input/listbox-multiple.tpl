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



<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld = new Ext.ux.form.SuperBoxSelect({
    {/literal}
        xtype:'superboxselect'
        ,transform: 'tv{$tv->id}'
        ,id: 'tv{$tv->id}'
        ,triggerAction: 'all'
        ,mode: 'local'
        ,extraItemCls: 'x-tag'
        ,expandBtnCls: 'x-form-trigger'
        ,clearBtnCls: 'x-form-trigger'
        ,width: 400
        ,displayField: "text"
        ,valueField: "value"
        ,resizable: true
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}

        {if $params.title},title: '{$params.title}'{/if}
        {if $params.listWidth},listWidth: {$params.listWidth}{/if}
        ,maxHeight: {if $params.maxHeight|default}{$params.maxHeight}{else}300{/if}
        {if $params.typeAhead}
            ,typeAhead: true
            ,typeAheadDelay: {if $params.typeAheadDelay && $params.typeAheadDelay != ''}{$params.typeAheadDelay}{else}250{/if}
            ,editable: true
        {else}
            ,typeAhead: false
        {/if}
        {if $params.listEmptyText}
            ,listEmptyText: '{$params.listEmptyText}'
        {/if}
        ,forceSelection: true
        ,stackItems: {if $params.stackItems && $params.stackItems != 'false'}true{else}false{/if}
        ,msgTarget: 'under'

        {literal}
        ,listeners: {
            'select': {fn:MODx.fireResourceFormChange, scope:this}
            ,'beforeadditem': {fn:MODx.fireResourceFormChange, scope:this}
            ,'newitem': {fn:function(bs,v,f) {
                bs.addNewItem({"id": v,"text": v});
                MODx.fireResourceFormChange();
                return true;
            },scope:this}
            ,'beforeremoveitem': {fn:MODx.fireResourceFormChange, scope:this}
            ,'clear': {fn:MODx.fireResourceFormChange, scope:this}
        }
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>
