<div style="float: right; z-index: 200000000;">
    <div class="ux-row-action" id="modx-tv-refresh" style="z-index: 200000000;"onclick="MODx.refreshTVs();">
        <div class="ux-row-action-item ux-row-action-text" style="z-index: 200000000;"><span>{$_lang.reload}</span></div>
    </div>
</div>

<input type="hidden" name="tvs" value="1" />
<div id="modx-tv-tabs">
{foreach from=$categories item=category}
{if count($category->tvs) > 0}

    <div id="modx-tv-tab{$category->id}" class="x-tab" title="{$category->category|default:$_lang.uncategorized|ucfirst}">
    
    <table class="classy">
    <tbody>
    {foreach from=$category->tvs item=tv name='tv'}
    <tr class="{cycle values=',odd'}">
        <th width="150">
            <label class="dashed" style="cursor: pointer;" title="{$tv->description}" for="tv{$tv->id}">{$tv->caption}</label>
            <br />
            <span style="font-size: .8em; font-weight: normal">[[*{$tv->name}]]</span>
            {if $tv->get('type') EQ 'richtext'}
            <br /><button id="tv{$tv->id}-toggle" class="modx-richtext-toggle">{$_lang.toggle_richtext}</button>
            {/if}
        </th>
        <td valign="top" style="position:relative" class="x-form-element">
            <input type="hidden" id="tvdef{$tv->id}" value="{$tv->default_text|escape}" />
            {$tv->get('formElement')}  
        </td>
        <td>
            <div class="ux-row-action" onclick="MODx.resetTV({$tv->get('id')});">
                <div class="ux-row-action-item ux-row-action-text"><span>{$_lang.set_to_default}</span></div>
            </div>
            {if $tv->get('inherited')}<em>({$_lang.tv_value_inherited})</em>{/if}
            
        </td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="2">{$_lang.tmplvars_novars}</td>
    </tr>
    {/foreach}
    </tbody>
    </table>
    </div>
{/if}
{/foreach}
</div>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    
    MODx.resetTV = function(id) {
        var i = Ext.get('tv'+id);
        var d = Ext.get('tvdef'+id);
        
        if (i) {
            i.dom.value = d.dom.value;
            i.dom.checked = d.dom.value ? true : false;
        }
        var c = Ext.getCmp('tv'+id);
        if (c) {
            if (c.xtype == 'checkboxgroup' || c.xtype == 'radiogroup') {
                var cbs = d.dom.value.split(',');                                
                for (var i=0;i<c.items.length;i++) {
                    if (c.items.items[i]) {
                        c.items.items[i].setValue(cbs.indexOf(c.items.items[i].id) != -1 ? true : false);
                    }
                } 
            } else {
                c.setValue(d.dom.value);
            }
        }
    };
    MODx.refreshTVs = function() {
        Ext.getCmp('modx-panel-resource-tv').refreshTVs();
    };
    MODx.load({
        xtype: 'tabpanel'
        ,applyTo: 'modx-tv-tabs'
        ,activeTab: 0
        ,autoTabs: true
        ,plain: true
        ,autoWidth: true
        ,border: false
        ,defaults: {
            bodyStyle: 'padding: 5px;'
            
        }
        ,deferredRender: false
    });
});    
// ]]>
</script>
{/literal}
