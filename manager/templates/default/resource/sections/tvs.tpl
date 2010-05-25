<div class="modx-tv-reload-btn">
    <div class="ux-row-action" id="modx-tv-refresh" onclick="MODx.refreshTVs();">
        <div class="ux-row-action-item ux-row-action-text"><span>{$_lang.reload}</span></div>
    </div>
</div>

<input type="hidden" name="tvs" value="1" />
<div id="modx-tv-tabs">
{foreach from=$categories item=category}
{if count($category->tvs) > 0}

    <div id="modx-tv-tab{$category->id}" class="x-tab" title="{$category->category|default:$_lang.uncategorized|ucfirst}">
    
    <table class="modx-tv-table" cellspacing="0" width="90%">
    <tbody>
    {foreach from=$category->tvs item=tv name='tv'}
    <tr class="{cycle values=',alt'} modx-tv-tr">
        <th width="150" class="aright modx-tv-th">
            <label for="tv{$tv->id}">{$tv->caption}</label>
            <br />
            <span class="tvtag">[[*{$tv->name}]]</span>
            <br /><span class="tv-description">{$tv->description}</span>
        </th>
        <td class="x-form-element modx-tv-td">
            <input type="hidden" id="tvdef{$tv->id}" value="{$tv->default_text|escape}" />
            {$tv->get('formElement')}
            <br class="clear" />
        </td>
        <td class="aleft modx-tv-td" style="width: 200px !important;">
            {if $tv->get('type') NEQ 'richtext'}
            <div class="ux-row-action" onclick="MODx.resetTV({$tv->get('id')});">
                <div class="ux-row-action-item ux-row-action-text" id="modx-reset-tv-{$tv->id}"><span>{$_lang.set_to_default}</span></div>
            </div>
            {/if}
            {if $tv->get('inherited')}<br class="clear" /><em>({$_lang.tv_value_inherited})</em>{/if}            
        </td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="2" class="modx-tv-td">{$_lang.tmplvars_novars}</td>
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
        Ext.getCmp('modx-panel-resource').markDirty();
    };
    MODx.refreshTVs = function() {
        if (MODx.unloadTVRTE) { MODx.unloadTVRTE(); }
        Ext.getCmp('modx-panel-resource-tv').refreshTVs();
    };
    {/literal}{if $tvcount GT 0}{literal}
    MODx.load({
        xtype: 'modx-tabs'
        ,applyTo: 'modx-tv-tabs'
        ,activeTab: 0
        ,autoTabs: true
        ,border: false
        ,plain: true
        ,width: Ext.getCmp('modx-panel-resource-tv').getWidth() - 30
        ,hideMode: 'offsets'
        ,autoScroll: true
        ,defaults: {
            bodyStyle: 'padding: 5px;'
            ,autoScroll: true
            ,autoHeight: true
        }
        ,deferredRender: false
    });
    {/literal}{/if}{literal}
});    
// ]]>
</script>
{/literal}

<br class="clear" />