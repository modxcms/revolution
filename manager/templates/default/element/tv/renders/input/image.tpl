<div id="tv-image-{$tv->id}"></div>
<div id="tv-image-preview-{$tv->id}" class="modx-tv-image-preview">
    {if $tv->value}<img src="{$_config.connectors_url}system/phpthumb.php?w=400&h=400&aoe=0&far=0&src={$tv->value}&source={$source}" alt="" />{/if}
</div>
{if $disabled}
<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld{/literal}{$tv->id}{literal} = MODx.load({
    {/literal}
        xtype: 'displayfield'
        ,tv: '{$tv->id}'
        ,renderTo: 'tv-image-{$tv->id}'
        ,value: '{$tv->value|escape}'
        ,width: 400
        ,msgTarget: 'under'
    {literal}
    });
});
{/literal}
// ]]>
</script>
{else}
<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld{/literal}{$tv->id}{literal} = MODx.load({
    {/literal}
        xtype: 'modx-panel-tv-image'
        ,renderTo: 'tv-image-{$tv->id}'
        ,tv: '{$tv->id}'
        ,value: '{$tv->value|escape}'
        ,relativeValue: '{$tv->value|escape}'
        ,width: 400
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        ,wctx: '{if $params.wctx|default}{$params.wctx}{else}web{/if}'
        {if $params.openTo|default},openTo: '{$params.openTo|replace:"'":"\\'"}'{/if}
        ,source: '{$source}'
    {literal}
        ,msgTarget: 'under'
        ,listeners: {
            'select': {fn:function(data) {
                MODx.fireResourceFormChange();
                var d = Ext.get('tv-image-preview-{/literal}{$tv->id}{literal}');
                if (Ext.isEmpty(data.url)) {
                    d.update('');
                } else {
                    {/literal}
                    d.update('<img src="{$_config.connectors_url}system/phpthumb.php?w=400&h=400&aoe=0&far=0&src='+data.url+'&wctx={$ctx}&source={$source}" alt="" />');
                    {literal}
                }
            }}
        }
    });
    MODx.makeDroppable(Ext.get('tv-image-{/literal}{$tv->id}{literal}'),function(v) {
        var cb = Ext.getCmp('tvbrowser{/literal}{$tv->id}{literal}');
        if (cb) {
            cb.setValue(v);
            cb.fireEvent('select',{relativeUrl:v});
        }
        return '';
    });
});
{/literal}
// ]]>
</script>
{/if}
