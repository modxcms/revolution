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
    ,relativeValue: '{$tv->relativeValue|escape}'
    ,width: '97%'
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