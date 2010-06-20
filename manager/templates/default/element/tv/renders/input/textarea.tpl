<textarea id="tv{$tv->id}" name="tv{$tv->id}" rows="15">{$tv->get('value')|escape}</textarea>

<script type="text/javascript">
// <![CDATA[
{literal}
MODx.load({
{/literal}
    xtype: 'textarea'
    ,applyTo: 'tv{$tv->id}'
    ,value: '{$tv->get('value')|escape:'javascript'}'
    ,height: 140
    ,width: '97%'
    ,enableKeyEvents: true
{literal}
    ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
});
{/literal}
// ]]>
</script>