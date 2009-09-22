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
            {$tv->get('formElement')}
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
    /*var tvtabs = new MODx.Tabs({
        renderTo: 'tvtabs_div'
        ,items: [
            {
            {/literal}
                contentEl: 'tvtab{$category->id}'
                ,title: '{$category->category|capitalize} ({$category->tvs|@count})'
            {literal}
            }
        ]
    });*/
});
// ]]>
</script>
{/literal}
