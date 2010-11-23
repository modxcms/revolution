<textarea id="tv{$tv->id}" name="tv{$tv->id}" class="textarea" rows="5"
>{$tv->get('value')|escape}</textarea>

<script type="text/javascript">
// <![CDATA[
{literal}
var fld = MODx.load({
{/literal}
    xtype: 'textarea'
    ,applyTo: 'tv{$tv->id}'
    ,width: '97%'
    ,grow: true
    ,enableKeyEvents: true
{literal}
    ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
});
MODx.makeDroppable(fld);
{/literal}
// ]]>
</script>