<h2>{$_lang.tmplvars}</h2>

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
        </th>
        <td valign="top" style="position:relative" class="x-form-element">
            <input type="hidden" id="tvdef{$tv->id}" value="{$tv->default_text}" />
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
        
        i.dom.value = d.dom.value;
        i.dom.checked = d.dom.value ? true : false;        
    };
});
// ]]>
</script>
{/literal}
