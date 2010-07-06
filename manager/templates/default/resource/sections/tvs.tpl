<input type="hidden" name="tvs" value="1" />
<div id="modx-tv-tabs">
{foreach from=$categories item=category}
{if count($category->tvs) > 0}

    <div id="modx-tv-tab{$category->id}" class="x-tab" title="{$category->category|default:$_lang.uncategorized|ucfirst}">
    
    {foreach from=$category->tvs item=tv name='tv'}
    <div class="x-form-item x-tab-item {cycle values=",alt"} modx-tv" id="tv{$tv->id}-tr">
        <label for="tv{$tv->id}" class="modx-tv-label">

            {if $showCheckbox}<input type="checkbox" name="tv{$tv->id}-checkbox" class="modx-tv-checkbox" value="1" />{/if}
            <span class="modx-tv-caption" id="tv{$tv->id}-caption">{$tv->caption}</span>
            <a class="modx-tv-reset" href="javascript:;" onclick="MODx.resetTV({$tv->id});" title="{$_lang.set_to_default}"></a>

            {if $tv->description}<span class="modx-tv-description">{$tv->description}</span>{/if}
        </label>
        <div class="x-form-element modx-tv-form-element" style="padding-left: 221px;">
            <input type="hidden" id="tvdef{$tv->id}" value="{$tv->default_text|escape}" />
            {$tv->get('formElement')}
        </div>

        <br class="clear" />
    </div>
    <script type="text/javascript">{literal}Ext.onReady(function() { new Ext.ToolTip({{/literal}target: 'tv{$tv->id}-caption',html: '[[*{$tv->name}]]'{literal}});});{/literal}</script>
    {/foreach}

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
        ,width: Ext.getCmp('modx-panel-resource').getWidth() - 30
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

{$OnResourceTVFormRender}

<br class="clear" />