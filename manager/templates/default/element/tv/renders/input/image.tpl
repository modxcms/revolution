<div id="tvbrowser{$tv->id}"></div>
<div id="tv-image-{$tv->id}" style="width: 97%"></div>
<div id="tv-image-preview-{$tv->id}">
    {if $tv->value}<img src="{$base_url}{$tv->value}" width="150" height="150" alt="" />{/if}
</div>

<script type="text/javascript">
// <![CDATA[
{literal}
MODx.load({
{/literal}
    xtype: 'modx-panel-tv-image'
    ,renderTo: 'tv-image-{$tv->id}'
    ,tv: '{$tv->id}'
    ,value: '{$tv->value|escape}'
    ,width: '97%'
{literal}
    ,listeners: {
        'select': {fn:function(data) {
            MODx.fireResourceFormChange();
            var d = Ext.get('tv-image-preview-{/literal}{$tv->id}{literal}');
            d.update('<img src="'+data.url+'" width="150" height="150" alt="" />');
        }, scope:this}
    }
});
{/literal}
// ]]>
</script>