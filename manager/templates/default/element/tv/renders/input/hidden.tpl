<input id="tv{$tv->id}" name="tv{$tv->id}" type="hidden" value="{$tv->get('value')|escape}" />

<script>
// <![CDATA[
{literal}
MODx.on('ready',function() {
    const fld = MODx.load({
    {/literal}
        xtype: 'hidden'
        ,itemId: 'tv{$tv->id}'
        ,applyTo: 'tv{$tv->id}'
        ,value: '{$tv->get('value')|escape:'javascript'}'
    {literal}
    });
    const p = Ext.getCmp('modx-panel-resource');
    if (p) {
        p.add(fld);
        p.doLayout();
    }
});
{/literal}
// ]]>
</script>
