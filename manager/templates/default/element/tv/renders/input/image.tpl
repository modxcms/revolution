<div id="tvbrowser{$tv->id}"></div>
<div id="tvpanel{$tv->id}"></div>

<script type="text/javascript">
{literal}
MODx.load({
{/literal}
    xtype: 'modx-panel-tv-image'
    ,renderTo: 'tvpanel{$tv->id}'
    ,tv: '{$tv->id}'
    ,value: '{$tv->value|escape}'
    ,width: '97%'
{literal}
    ,listeners: { 'select': { fn:MODx.fireResourceFormChange, scope:this}}
});
{/literal}
</script>