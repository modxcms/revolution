<div id="tvpanel{$tv->id}"></div>

{if $disabled}
<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld{/literal}{$tv->id}{literal} = MODx.load({
    {/literal}
        xtype: 'displayfield'
        ,tv: '{$tv->id}'
        ,renderTo: 'tvpanel{$tv->id}'
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
<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    var fld{/literal}{$tv->id}{literal} = MODx.load({
    {/literal}
        xtype: 'modx-panel-tv-file'
        ,renderTo: 'tvpanel{$tv->id}'
        ,tv: '{$tv->id}'
        ,value: '{$tv->value|escape}'
        ,relativeValue: '{$tv->value|escape}'
        ,width: 400
        ,msgTarget: 'under'
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        ,source: '{$source}'

        {if $params.allowedFileTypes},allowedFileTypes: '{$params.allowedFileTypes}'{/if}
        ,wctx: '{if $params.wctx|default}{$params.wctx}{else}web{/if}'
        {if $params.openTo|default},openTo: '{$params.openTo|replace:"'":"\\'"}'{/if}

    {literal}
        ,listeners: {'select': {fn:MODx.fireResourceFormChange, scope:this}}
        ,validate: function () {
            var value = Ext.getCmp('tv{/literal}{$tv->id}{literal}').value;
            return !(!this.allowBlank && (value.length < 1));
        }
        ,markInvalid : Ext.emptyFn
        ,clearInvalid : Ext.emptyFn
    });
    MODx.makeDroppable(Ext.get('tvpanel{/literal}{$tv->id}{literal}'),function(v) {
        var cb = Ext.getCmp('tvbrowser{/literal}{$tv->id}{literal}');
        if (cb) {
            cb.setValue(v);
            cb.fireEvent('select',{relativeUrl:v});
        }
        return '';
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld{/literal}{$tv->id}{literal});
});
{/literal}
// ]]>
</script>
{/if}
