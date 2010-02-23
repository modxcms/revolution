{if $which_editor EQ '' OR $which_editor EQ 'MODxEditor'}
<input type="hidden" name="tv{$tv->id}" id="tv{$tv->id}-hidden" />
<div id="tv{$tv->id}-rt" style="height: 380px;"></div>
<script type="text/javascript">
// <![CDATA[
{literal}
new Ext.Panel({{/literal}
    renderTo: 'tv{$tv->id}-rt'
    ,width: '97%'
    ,frame: true
    ,layout: 'fit'
    ,items: {literal}{{/literal}
        xtype: 'modx-richtext'
        ,name: 'tv-{$tv->id}-rt'
        ,cls: 'modx-richtext'
        ,stylesheet: MODx.config.editor_css_path || ''
        ,anchor: '100%'
        ,listeners: {literal}{
            'sync': {fn:function(ed,html) {
                Ext.get('tv{/literal}{$tv->id}{literal}-hidden').set({ value: html });
                MODx.fireResourceFormChange();
            },scope:this}
        }{/literal}
        
        ,value: '{$tv->get('value')|escape:'javascript'}'
    {literal}}
});{/literal}
// ]]>
</script>
{else}
<textarea id="tv{$tv->id}" name="tv{$tv->id}"
    class="textarea modx-richtext"
    cols="40" rows="15"
    {literal}onchange="MODx.fireResourceFormChange();"{/literal}
>{$tv->get('value')|escape}</textarea>
{/if}