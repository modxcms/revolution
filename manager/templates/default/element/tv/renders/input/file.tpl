<div id="tvbrowser{$tv->id}"></div>
<div id="tvpanel{$tv->id}"></div>

<script type="text/javascript">
// <![CDATA[
{literal}
var fld{/literal}{$tv->id}{literal} = MODx.load({
{/literal}
    xtype: 'modx-panel-tv-file'
    ,renderTo: 'tvpanel{$tv->id}'
    ,tv: '{$tv->id}'
    ,value: '{$tv->value|escape}'
    ,relativeValue: '{$tv->value|escape}'
    ,width: '97%'
    ,msgTarget: 'under'
    ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
    {if $params.basePath},basePath: "{$params.basePath}"{/if}
    ,basePathRelative: {if $params.basePathRelative}true{else}false{/if}
    {if $params.baseUrl},baseUrl: "{$params.baseUrl}"{/if}
    ,baseUrlRelative: {if $params.baseUrlRelative}true{else}false{/if}
    {if $params.allowedFileTypes},allowedFileTypes: '{$params.allowedFileTypes}'{/if}
    
{literal}
    ,listeners: { 'select': { fn:MODx.fireResourceFormChange, scope:this}}
});
MODx.makeDroppable(Ext.get('tv{/literal}{$tv->id}{literal}'),function(v) {
    var cb = Ext.getCmp('tv{/literal}{$tv->id}{literal}');
    if (cb) {
        cb.setValue(v);
    }
    fld{/literal}{$tv->id}{literal}.fireEvent('select',{relativeUrl:v});
    return '';
});

{/literal}
// ]]>
</script>