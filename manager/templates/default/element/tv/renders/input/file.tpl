<div id="tvpanel{$tv->id}"></div>

{if $disabled}
<script>
// <![CDATA[
{literal}
Ext.onReady(function() {
    const fld = MODx.load({
    {/literal}
        xtype: 'displayfield'
        ,itemId: 'tv{$tv->id}'
        ,tv: '{$tv->id}'
        ,renderTo: 'tvpanel{$tv->id}'
        {if $tv->value != ''}
        ,value: '{$tv->value|escape}'
        {/if}
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
    const fld = MODx.load({
    {/literal}
        xtype: 'modx-panel-tv-file'
        ,renderTo: 'tvpanel{$tv->id}'
        ,tv: '{$tv->id}'
        ,itemId: 'tv{$tv->id}'
        {if $tv->value != ''}
        ,value: '{$tv->value|escape}'
        ,relativeValue: '{$tv->value|escape}'
        {/if}
        ,width: 400
        ,msgTarget: 'under'
        ,source: '{$source}'
        ,wctx: '{if $params.wctx|default}{$params.wctx}{else}web{/if}'
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        {if $params.allowedFileTypes},allowedFileTypes: '{$params.allowedFileTypes}'{/if}
        {if $params.openTo|default},openTo: '{$params.openTo|replace:"'":"\\'"}'{/if}
    {literal}
        ,listeners: {
            select: {
                fn: MODx.fireResourceFormChange,
                scope: this
            }
        }
        ,validate: function() {
            return Ext.getCmp('tvbrowser{/literal}{$tv->id}{literal}').validate();
        }
    });
    MODx.makeDroppable(Ext.get('tvpanel{/literal}{$tv->id}{literal}'), function(v) {
        const cb = Ext.getCmp('tvbrowser{/literal}{$tv->id}{literal}');
        if (cb) {
            cb.setValue(v);
            cb.fireEvent('select', {relativeUrl: v});
        }
        return '';
    });
    Ext.getCmp('modx-panel-resource').getForm().add(fld);
});
{/literal}
// ]]>
</script>
{/if}
