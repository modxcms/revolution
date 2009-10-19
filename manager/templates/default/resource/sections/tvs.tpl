<div style="float: right;">
    <button id="modx-tv-refresh" onclick="MODx.refreshTVs();">{$_lang.reload}</button>
</div>

<input type="hidden" name="tvs" value="1" />

<div id="tvtabs_div">
{foreach from=$categories item=category}
{if count($category->tvs) > 0}
    <div id="tvtab{$category->id}">
    
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
            <input type="button" onclick="MODx.resetTV({$tv->get('id')});" value="{$_lang.set_to_default}" />
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
            if (c.xtype == 'checkboxgroup') {
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
});
// ]]>
</script>
{/literal}
