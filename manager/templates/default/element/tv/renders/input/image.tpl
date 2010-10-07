<div id="tvbrowser{$tv->id}"></div>
<div id="tv-image-{$tv->id}" style="width: 97%"></div>
<div id="tv-image-preview-{$tv->id}">
    {if $tv->value}<img src="{$_config.connectors_url}system/phpthumb.php?h=150&w=150&src={$tv->value}" alt="" />{/if}
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
            if (Ext.isEmpty(data.relativeUrl)) {
                d.update('');
            } else {
                d.update('<img src="'+MODx.config.connectors_url+'system/phpthumb.php?h=150&w=150&src='+data.relativeUrl+'" alt="" />');
            }
        }, scope:this}
    }
});
{/literal}
// ]]>
</script>