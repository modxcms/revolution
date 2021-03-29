{$OnResourceTVFormPrerender}

<input type="hidden" name="tvs" value="1" />
<div id="modx-tv-tabs" class="x-form-label-top">
{foreach from=$categories item=category}
{if count($category.tvs) > 0}

    <div id="modx-tv-tab{$category.id}" class="x-tab{if $category.hidden}-hidden{/if}" title="{$category.category}">
    {foreach from=$category.tvs item=tv name='tv'}
{if $tv->type NEQ "hidden"}
    <div class="modx-tv-type-{$tv->type} x-form-item x-tab-item {cycle values=",alt"} modx-tv{if $smarty.foreach.tv.first} tv-first{/if}{if $smarty.foreach.tv.last} tv-last{/if}" id="tv{$tv->id}-tr">
        <label for="tv{$tv->id}" class="x-form-item-label modx-tv-label">
            <div class="modx-tv-label-title">
                {if $showCheckbox|default}<input type="checkbox" name="tv{$tv->id}-checkbox" class="modx-tv-checkbox" value="1" />{/if}
                <span class="modx-tv-caption" id="tv{$tv->id}-caption">{if $tv->caption}{$tv->caption}{else}{$tv->name}{/if}</span>
            </div>
            <a class="modx-tv-reset" id="modx-tv-reset-{$tv->id}" title="{$_lang.set_to_default}"></a>
            {if $tv->description}
            <span class="modx-tv-label-description">{$tv->description}</span>
            {/if}
        </label>
        {if $tv->inherited}<span class="modx-tv-inherited">{$_lang.tv_value_inherited}</span>{/if}
        <div class="x-form-element modx-tv-form-element">
            <input type="hidden" id="tvdef{$tv->id}" value="{$tv->default_text|escape}" />
            {$tv->get('formElement')}
        </div>
    </div>
    <script>{literal}Ext.onReady(function() { new Ext.ToolTip({{/literal}target: 'tv{$tv->id}-caption',html: '[[*{$tv->name}]]'{literal}});});{/literal}</script>
{else}
    <input type="hidden" id="tvdef{$tv->id}" value="{$tv->default_text|escape}" />
    {$tv->get('formElement')}
{/if}
    {/foreach}

    <div class="clear"></div>

    </div>
{/if}
{/foreach}
</div>
{literal}
<script>
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
                        c.items.items[i].setValue(cbs.indexOf(c.items.items[i].id) != -1);
                    }
                }
            } else {
                c.setValue(d.dom.value);
            }
        }
        var p = Ext.getCmp('modx-panel-resource');
        if (p) {
            p.markDirty();
            p.fireEvent('tv-reset',{id:id});
        }
    };

    Ext.select('.modx-tv-reset').on('click',function(e,t,o) {
        var id = t.id.split('-');
        id = id[3];
        MODx.resetTV(id);
    });
    MODx.refreshTVs = function() {
        if (MODx.unloadTVRTE) { MODx.unloadTVRTE(); }
        Ext.getCmp('modx-panel-resource-tv').refreshTVs();
    };
    {/literal}{if $tvcount GT 0}{literal}
    MODx.load({
        xtype: 'modx-vtabs'
        ,applyTo: 'modx-tv-tabs'
        ,autoTabs: true
        ,border: false
        ,plain: true
        ,deferredRender: false
        ,id: 'modx-resource-vtabs'
        ,headerCfg: {
            tag: 'div'
            ,cls: 'x-tab-panel-header vertical-tabs-header'
            ,id: 'modx-resource-vtabs-header'
            ,html: MODx.config.show_tv_categories_header === true ? '<h4 id="modx-resource-vtabs-header-title">'+_('categories')+'</h4>' : ''
        }
        ,listeners: {
            beforeadd: function (tabpanel, comp) {
                if (comp.contentEl && (Ext.get(comp.contentEl).child('.modx-tv') === null)) {
                    return false;
                }
            }
            ,afterrender: function (tabpanel) {
                if (tabpanel.items.length === 0) {
                    Ext.getCmp('modx-resource-tabs').hideTabStripItem('modx-panel-resource-tv');
                }
            }
        }
    });
    {/literal}{/if}

    MODx.tvCounts = {$tvCounts};
    MODx.tvMap = {$tvMap};
    {literal}
});
// ]]>
</script>
{/literal}

{$OnResourceTVFormRender}

    <div class="clear"></div>
