<div id="tvbrowser{$tv->id}"></div>
<div id="tv-image-{$tv->id}" style="width: 97%"></div>
<div id="tv-image-preview-{$tv->id}">
    {if $tv->value}<img src="{$_config.connectors_url}system/phpthumb.php?h=150&w=150&src={$tv->value}&basePath={$params.basePath}&basePathRelative={$params.basePathRelative}&baseUrl={$params.baseUrl}&baseUrlRelative={$params.baseUrlRelative}&baseUrlPrependCheckSlash={if $params.baseUrlPrependCheckSlash}1{else}0{/if}" alt="" />{/if}
</div>

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
        ,width: '97%'
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        {if $params.basePath},basePath: "{$params.basePath}"{/if}
        ,basePathRelative: {if $params.basePathRelative}true{else}false{/if}
        {if $params.baseUrl},baseUrl: "{$params.baseUrl}"{/if}
        ,baseUrlRelative: {if $params.baseUrlRelative}true{else}false{/if}
        {if $params.allowedFileTypes},allowedFileTypes: '{$params.allowedFileTypes}'{/if}
        ,wctx: '{if $params.wctx}{$params.wctx}{else}web{/if}'
        {if $params.openTo},openTo: '{$params.openTo}'{/if}
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
                    d.update('<img src="'+MODx.config.connectors_url+'system/phpthumb.php?h=150&w=150&src='+data.url+'&wctx={$ctx}&basePath={$params.basePath}&basePathRelative={if $params.basePathRelative}1{else}0{/if}&baseUrl={$params.baseUrl}&baseUrlRelative={if $params.baseUrlRelative}1{else}0{/if}&baseUrlPrependCheckSlash={if $params.baseUrlPrependCheckSlash}1{else}0{/if}" alt="" />');
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
